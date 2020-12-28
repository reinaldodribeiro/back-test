# Backend Test

Develop the workflow's REST API following the specification bellow and document it.

## Delivery instructions

Clone this project and push a private repository in the [GitHub](https://github.com/), [Gitlab](https://about.gitlab.com/) or [Bitbucket](https://bitbucket.org/). When you want to our review, write any information that you think important in the README.md and send an email to talentos@nuveo.ai. We'll follow your instructions to run your code and look the outcome. 

## Defining a workflow

|Name|Type|Description|
|-|-|-|
|UUID|UUID|workflow unique identifier|
|status|Enum(inserted, consumed)|workflow status|
|data|JSONB|workflow input|
|steps|Array|name of workflow steps

## Endpoints

|Verb|URL|Description|
|-|-|-|
|POST|/workflow|insert a workflow on database and on queue and respond request with the inserted workflow|
|PATCH|/workflow/{UUID}|update status from specific workflow|
|GET|/workflow|list all workflows|
|GET|/workflow/consume|consume a workflow from queue and generate a CSV file with workflow.Data|

## Technologies

- Go, C, C++, Python, Java or any other that you know
- PostgreSQL
- A message queue that you choose, but describe why you choose.

## Reinaldo D. Ribeiro

Neste projeto foi desenvolvido uma API Rest de acordo com o que foi solicitado, optei pelo framework Laravel/PHP para o desenvolvimento, e para o mensageiro utilizei o RabbitMQ.

## Requisitos para rodar o projeto

É necessário ter algumas tecnologias instaladas na maquina como:
- Php 5.6 ou posterior.
- Composer.
- PostgreSQL

## Configuração Inicial:
Após clonar o projeto é necessário realizar algumas configurações:

- 1º: Rodar o seguinte comando:
```
composer install
````
- 2º: É necessário criar um banco de dados. O nome para o mesmo fica a critério, pois a conexão é configurada no arquivo ".env". Aqui em minha máquina criei um database com nome "workflow_db".
- 3º: Criar um arquivo de configuração na raiz do projeto com o nome ".env", e utilizar como base o arquivo ".env_example", e configurar as variaveis de conexão com banco de dados.
 
 ```
 DB_CONNECTION=pgsql
 DB_HOST=127.0.0.1
 DB_PORT=5432
 DB_DATABASE=workflow_db
 DB_USERNAME=postgres
 DB_PASSWORD=postgres
````

- 4º Feito isso, é necessário criar a estrutura do banco de dados. Para isso criei uma migration que já faz isso, então execute o seguinte comando:
```
php artisan migrate
````
- 5º Siga o passo a passo para a instalação do RabbitMQ no site do https://www.rabbitmq.com/, caso prefire pode utilizar o docker para fazer uso do Rabbit.
```
docker run -d --hostname rabbitserver --name rabbitmq-server -p 15672:15672 -p 5672:5672 rabbitmq:3-management
````
- 6º Para rodar o projeto back-end basta rodar o seguinte comando na pasta do projeto:
```
php artisan serve
```` 
Será iniciado na porta 8000.

<b>BaseURL endpoint: http://127.0.0.1:8000/api/v1/ </b>

## Post /workflow
http://127.0.0.1:8000/api/v1/workflow

Neste endpoint é possível cadastrar um novo workflow e adiciona-lo a fila do rabbitmq como mostrado na imagem abaixo.

É obrigatório os campos "data" e "steps" com seus respectivos tipos.

```json

    {
        "data": "{\"Story\":\"CRUD Workflow\",\"User\":\"Reinaldo\"}",
        "steps": [
            "Create story",
            "Detail story",
            "Assign User",
            "Develop",
            "Finish Story"
        ]
    }
````
![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/POSTWorkflow.PNG)

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/PostgresSAVE.PNG)

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/RabbitQueue.PNG)

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/RabbitMessages.PNG)

### PATCH /workflow/{uuid}
http://127.0.0.1:8000/api/v1/workflow/b294f589-a0e5-46e2-ba33-c7ecba9cf927

No patch, optei por não ter que passar nada no body, somente mandar a requisição passando o uuid do workflow desejado e já realiza a alteração do status e retorna os dados daquele workflow.

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/PATCHWorkflow.PNG)
### GET /workflow

http://127.0.0.1:8000/api/v1/workflow

Lista todos os WorkFlows do banco de dados.

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/GETWorkflows.PNG)

### GET /workflow/consume

http://127.0.0.1:8000/api/v1/workflow/consume

Consome todas as mensagens pendentes estão na fila, e gera um CSV com os dados do WorkFlow.
OBS: O csv é gerado na pasta raiz do projeto, e o nome dados é no formato 'd-m-Y H'.csv

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/RabbitConsume.PNG)

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/FileCsvCreated.PNG)

![Alt text](https://raw.githubusercontent.com/reinaldodribeiro/back-test/master/public/images/FileCsvOpen.PNG)


### Considerações

Foi muito gratificante poder participar deste desafio ! Obrigado Nuveo Team pela oportunidade, até breve !
