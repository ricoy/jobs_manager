
# Jobs Manager

Este projeto é uma prova de conceito que tem o objetivo de demonstrar o conhecimento em programação, recursos da linguagem PHP e lógica de programação. Foram utilizados o [Slim Framework](https://www.slimframework.com/), que é um micro framework para a criação de APIs, o [PHPUnit](https://phpunit.de/), que é um framework de criação de testes unitários e o [Composer](https://getcomposer.org/), que é responsável pela gestão de dependências de pacotes. Além disso, foi utilizada a versão 7.4 do PHP nesta prova de conceito. Maiores informações sobre as versões das bibliotecas podem ser consultadas no composer.json do repositório.

**Desafio: Criar algoritmo que retorne um conjunto de arrays com as seguintes características:**

- Cada array do conjunto representa uma lista de Jobs a serem executados em sequência;
- Cada array deve conter jobs que sejam executados em, no máximo, 8h;
- Deve ser respeitada a data máxima de conclusão do Job;
- Todos os Jobs devem ser executados dentro da janela de execução (data início e fim).

**Solução:**
Para a resolução do problema, foi criada uma api de serviços com as seguintes operações:  

**/adicionar-job (POST):** permite incluir um agendamento de execução de um Job.  
Exemplo de uso:  
<code>
curl --location --request POST 'http://localhost:8081/adicionar-job' \
--header 'Content-Type: application/json' \
--data-raw '{
    "id": 3,
    "descricao": "Importação de dados de integração",
    "dataMaximaConclusao": "2019-11-11 08:00:00",
    "tempoEstimado": 6
}'
</code>  
Exemplo de retorno:  
<code>
{"id":3,"descricao":"Importa\u00e7\u00e3o de dados de integra\u00e7\u00e3o","dataMaximaConclusao":"2019-11-11
08:00:00","tempoEstimado":6}
</code>    

**/listar-fila-jobs/{dataHoraInicio}/{dataHoraFim} (GET):** Retorna uma lista com as sequências de jobs organizadas em janelas de execução de 8hs de acordo com a proposta do desafio. Essa lista é filtrada de acordo com a data de início e fim informados como parâmetro. As datas devem ter o formato: YYYY-MM-DDTHH:MM:SS. Exemplo de uso:  
<code>
curl --location --request GET 'http://localhost:8081/listar-fila-jobs/2019-11-10T12:00:00/2019-11-11T12:00:00'
</code>  
Exemplo retorno:  
<code>
[
    [
        1,
        3
    ]
]
</code>      

**/excluir-jobs (POST):** Permite excluir todos os jobs que já foram agendados. Exemplo de uso:  
<code>
curl --location --request POST 'http://localhost:8081/excluir-jobs'
</code>  
Exemplo retorno:  
<code>
{"message":"Jobs excluidos com sucesso!"}
</code> 

**Passos para instalação e configuração do projeto (Obs: É necessário ter o Docker instalado)**
- Clonar o repositório:  
<code>git clone https://github.com/ricoy/jobs_manager.git</code>
- Acessar o diretório da aplicação no local em que foi realizado o clone do repositório:  
<code>cd jobs_manager/</code>
- Executar a imagem docker do composer para instalação das dependências do projeto:    
<code>docker run --rm --interactive --tty --volume $PWD:/app composer install</code>
- Executar a imagem docker do php-cli para inicializar a aplicação em modo dev:  
<code>docker run --rm -p 8081:8000 -d -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.4-cli php -S 0.0.0.0:8000</code>
- Acessar a url: http://127.0.0.1:8081. A porta pode ser alterada no comando anterior, caso necessário. Uma tela de apresentação deverá aparecer exibindo os resultados dos testes unitários executados como na imagem abaixo:

![image](https://user-images.githubusercontent.com/1377278/130885910-7cd9f7df-7fb5-4c30-8679-d46fd86ab80c.png)

**Postman**  
Na raiz do repositório há uma collection (JobsManager.postman_collection.json) do [Postman](https://www.postman.com/) que pode ser utilizada para realizar testes desta API. As informações sobre instalação e utilização desta ferramenta podem ser consultadas no site oficial.
![image](https://user-images.githubusercontent.com/1377278/130887804-07f276e7-a063-4578-8eb9-4505193c1168.png)



