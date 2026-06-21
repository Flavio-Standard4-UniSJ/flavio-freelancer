# Flávio Freelancer - Backend PHP de Depoimentos Google

Este projeto adiciona um backend PHP que busca avaliações do Google Places API e exibe depoimentos reais na landing page.

## Estrutura

- `index.html` - front-end principal
- `estilo.css` - estilos do site
- `api/google-reviews.php` - endpoint PHP que consulta o Google Places API
- `.env.example` - exemplo de variáveis de ambiente necessárias

## Como usar

1. Crie um projeto no Google Cloud e ative a API Google Places.
2. Gere uma chave de API e habilite o recurso `Places API`.
3. Obtenha o `place_id` do seu negócio. Você pode usar a ferramenta do Google Place ID Finder:
   - https://developers.google.com/maps/documentation/places/web-service/place-id
4. Configure as variáveis de ambiente no seu servidor PHP:
   - `GOOGLE_PLACES_API_KEY`
   - `GOOGLE_PLACES_PLACE_ID`

## Exemplo de deploy

### GitHub
O GitHub serve para armazenar o código e versionar o projeto, mas não executa PHP diretamente no GitHub Pages.

### Hospedagem PHP
Para rodar o backend, publique neste repositório em um servidor que suporte PHP, como:
- Hostinger
- Locaweb
- Umbler
- DigitalOcean App Platform (com container PHP)
- VPS com Apache/Nginx + PHP

### Localmente
Execute em seu computador com PHP instalado:

```bash
php -S localhost:8000
```

Acesse `http://localhost:8000/index.html`.

## Front-end

O `index.html` já busca o endpoint em `api/google-reviews.php` e carrega até 3 avaliações.

## Observações

- Mantenha a chave do Google API fora do repositório.
- Ajuste o `place_id` e a chave na configuração do servidor.
