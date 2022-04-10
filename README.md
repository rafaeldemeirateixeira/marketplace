# Marketplace
API que implementa cadastros de serviços possibilitando a contratação e agendamento online. O desenvolvimento será realizado em pequenos ciclos de 
MVP(Minimum Viable Product – ou Produto Mínimo Viável).

Inicialmente será comtemplado 2 atores principais, usuários e prestador de serviços.
### Usuários
- É o contratante dos serviços disponibilizados pelos prestadores.

### Prestadores de Serviços
- São os agentes que lançaram ofertas de serviços diversos.

## Pré-requisitos de desenvolvimento
- Comtemplar 100% das regras e normativas da LGPD(Lei geral de proteção de dados);

## MVP 1
- Cadastro de prestadores de serviços;
- Cadastro de usuários;
- Autenticação de usuários e prestadores de serviço;
- Cadastro de serviços;
- Agendamento de serviços;
- Avaliação dos prestadores de serviço.

## MVP 2
- Validação de celular;
- Validação de email;
- Esqueci minha senha;
- Notificação de serviços contratados aos usuários;
- Capcha no cadastro de serviços.

## MVP 3
- Compartilhamento de serviços por mídias sociais;
- Cancelamento de serviços pelo usuário;
- Segmentação por tipo de serviços;
- Painel de indicadores do Marketplace;

## MVP 4
- Analisador de conteúdo para fazer a validação dos serviços ofertados;
- Publicador de serviços cadastrado pelos prestadores;
- Selo de Pontuação para prestadores de serviços;
- Envio de imagens no cadastro de serviço para prestadores com Selo de Pontuação;
- Lista de bloqueio para prestadores de serviços;

## MVP 5
- OCR de imagens para detecção de conteúdo impróprio;
- Selo de pontuação para usuários;
- Termos de uso e privacidade;

## MVP 6
- Suporte aos usuários e prestadores de serviços;

## Instalação do projeto

```bash
$ docker-compose up -d
$ docker exec -it php_app composer install
$ docker exec -it php_app bin/console doctrine:migration:migrate
```
