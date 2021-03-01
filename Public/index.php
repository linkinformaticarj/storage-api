<?php
if (preg_match('/\.(?:png|jpg|jpeg|gif|zip)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // serve the requested resource as-is.
}
error_reporting(E_ERROR);

require __DIR__ . '/../vendor/autoload.php';

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;
use \EDGVI10\Helpers;

Helpers::cors();

$env = Helpers::getEnv(__DIR__ . "/../.env");

$debug = $env->debug ?? false;

setlocale(LC_TIME, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$config = ["settings" => ["displayErrorDetails" => true]];
$slim = new \Slim\App($config);

$slim->add(function ($request, $response, $next) {
    $headers = $request->getHeaders();

    $response = $next($request, $response);

    return $response;
});

$slim->get("[/]", function (Request $request, Response $response, array $array) {
    $return["success"] = false;
    $return["token"] = Helpers::base64url_encode("teste");
    return $response->withJson($return, 200);
});

// ###################### ROTAS PARA O DASHBOARD
$slim->group("/{auth}/{slug}", function ($slim) {
    $slim->get("/uploads", function (Request $request, Response $response, array $args) {
        global $env;
        $return["success"] = false;

        $type = $request->getHeader("HTTP_CONTENT_TYPE")[0];
        $return["type"] = $type;

        $filepath = __DIR__ . "/storage/{$args["slug"]}";

        $glob = glob("{$filepath}/*");

        foreach ($glob as $file) :
            $filename = array_reverse(explode("/", $file))[0];
            $fileurl = "{$env->storage_url}/storage/{$args["slug"]}/" . array_reverse(explode("/", $file))[0];
            $files[] = (object)[
                "name" => $filename,
                "url" => $fileurl,
            ];
        endforeach;

        $return["files"] = $files;

        if ($type === "application/json") :
            return $response->withJson($return, 200);
        else :
?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Arquivos</title>

                <style>
                    * {
                        margin: 0;
                        padding: 0;
                    }

                    body {
                        font-family: sans-serif;
                    }

                    li {
                        border-top: 1px solid #ddd;
                        padding: 5px;
                    }
                </style>
            </head>

            <body>
                <h3>Arquivos de: <?= $args["slug"]; ?></h3>
                <ul style="list-style: none;">
                    <?php
                    foreach ($files as $file) :
                    ?>
                        <li><?= $file->name ?><br>(<a href="<?= $file->url ?>"><?= $file->url ?></a>)</li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            </body>

            </html>
<?php
        // return $response->write($return, 200);
        endif;
    });

    $slim->post("/uploads", function (Request $request, Response $response, array $args) {
        global $env;
        $data = $request->getParsedBody();

        $return["success"] = false;
        $_body = $data;
        $_body["filedata"] = substr($_body["filedata"], 0, 50) . "...";
        $return["_body"] = $_body;

        $token = "bGlua2luZm8";
        $filetypes = [
            "image/png" => "png",
            "image/jpeg" => "jpg",
            "image/jpg" => "jpg",
        ];

        if ($args["auth"] !== $token) :
            $return["erros"][] = "Token incorreto";
        else :
            if (!isset($data["filedata"])) :
                $return["erros"][] = "Arquivo não enviado";
            else :
                $base64 = explode(",", $data["filedata"]);
                if (!isset($base64[1])) :
                    $return["erros"][] = "Arquivo não identificado";
                else :
                    $image = base64_decode($base64[1]);

                    $mimetype = $base64[0];
                    $mimetype = str_replace("data:", "", $mimetype);
                    $mimetype = str_replace(";base64", "", $mimetype);

                    $ext = $filetypes[$mimetype];
                    $filename = $data["filename"] ?? date("YmdHis") . ".{$ext}";
                    $filepath = __DIR__ . "/storage/{$args["slug"]}";

                    if (!is_dir($filepath)) :
                        mkdir($filepath);
                        $return["messages"][] = "Criando diretório \"{$args["slug"]}\"";
                    endif;

                    if (!is_dir($filepath)) :
                        $return["erros"][] = "Destino \"{$args["slug"]}\" não existe";
                    else :
                        $file = fopen("{$filepath}/{$filename}", "wb");
                        fwrite($file, $image);
                        fclose($file);

                        $return["success"] = true;
                        $return["mimetype"] = $mimetype;
                        $return["ext"] = $ext;
                        $return["filename"] = $filename;
                        $return["url"] = "{$env->storage_url}/{$args["slug"]}/{$filename}";
                    endif;

                // $response->write($image);
                // return $response->withHeader("Content-Type", $mimetype);
                endif;
            endif;
        endif;

        return $response->withJson($return);
    });
});

$slim->run();
