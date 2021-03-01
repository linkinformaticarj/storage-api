# API de upload de Arquivos para integrações do sistema RetLink

Rotas

Upload de arquivo <br>
`[POST] {baseurl}/{auth}/{slug}/uploads`
> ex: https://api.domain.com/[base64token|MD5token|id]/company_name/uploads

Lista todos os arquivos da empresa `{slug}`<br>
`[GET] {baseurl}/{auth}/{slug}/uploads`
> ex: https://api.domain.com/[base64token|MD5token|id]/company_name/uploads

Download ou Exibir arquivo `{slug}`<br>
`[GET] {baseurl}/{auth}/{slug}/uploads`
> ex: https://api.domain.com/[base64token|MD5token|id]/company_name/uploads/filename.jpg