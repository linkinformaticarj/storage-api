# API de upload de Arquivos para integrações do sistema RetLink

Rotas

Upload de arquivo <br>
`[POST] {baseurl}/{auth}/{slug}/uploads` : { filename, filedata }

Exemplo: https://api.domain.com/dGVzdGU/linkinfo/uploads

Body JSON:
```json
{
    "filename": "filename.png",
    "filedata": "data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAA..."
}
```
Retorno:
```json
{
  "success": true,
  "_body": {
    "filedata": "data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAA..."
  },
  "messages": [
    "Criando diretório \"linkinfo\""
  ],
  "mimetype": "image\/png",
  "ext": "png",
  "filename": "20210301190911.png",
  "url": "https://api.domain.com\/linkinfo\/20210301190911.png"
}
```

Lista todos os arquivos da empresa `{slug}`<br>
`[GET] {baseurl}/{auth}/{slug}/uploads`
> ex: https://api.domain.com/dGVzdGU/linkinfo/uploads

Download ou Exibir arquivo<br>
`[GET] {baseurl}/files/{slug}/{filename}`
> ex: https://api.domain.com/storage/files/linkinfo/filename.jpg
