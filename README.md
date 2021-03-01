# API de upload de Arquivos para integrações do sistema RetLink

Rotas

Upload de arquivo <br>
`[POST] {baseurl}/{auth}/{slug}/uploads`
> ex: https://api.domain.com/dGVzdGU/linkinfo/uploads

Lista todos os arquivos da empresa `{slug}`<br>
`[GET] {baseurl}/{auth}/{slug}/uploads`
> ex: https://api.domain.com/dGVzdGU/linkinfo/uploads

Download ou Exibir arquivo<br>
`[GET] {baseurl}/files/{slug}/{filename}`
> ex: https://api.domain.com/storage/files/linkinfo/filename.jpg
