<?php
use JobsManager\Job;
use JobsManager\JobsManager;
use JobsManager\JobStorageFile;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$container = new \Slim\Container($configuration);

$app = new \Slim\App($container);

$app->get('/', function (Request $request, Response $response) {

    $resultTests = shell_exec('./vendor/bin/phpunit tests --testdox');

    $template = <<<HTML

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <title>Jobs Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
</head>
  <body>
    <section class="py-5 jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Jobs Manager</h1>
            <h6>Desafio: Criar algoritmo que retorne um conjunto de arrays com as seguintes características:</h6>
            <ul class="list-unstyled">
                <li>Cada array do conjunto representa uma lista de Jobs a serem executados em sequência;</li>
                <li>Cada array deve conter jobs que sejam executados em, no máximo, 8h;</li>
                <li>Deve ser respeitada a data máxima de conclusão do Job;</li>
                <li>Todos os Jobs devem ser executados dentro da janela de execução (data início e fim).</li>
            </ul>
            <h6>Resultado dos testes unitários</h6>
            <code><pre>{$resultTests}</pre></code>
        </div>
        
        <a href="https://github.com/ricoy/jobs_manager" class="btn btn-primary my-2">Acessar GitHub</a>
    </section>
    
  </body>
</html>
HTML;

    echo $template;
});

$app->get('/listar-fila-jobs/{dataHoraInicio}/{dataHoraFim}', function (Request $request, Response $response, array $args) {

    try {
        $jobStorageFile = new JobStorageFile(__DIR__ . '/job.txt');
        $manager = new JobsManager($jobStorageFile);
        $jobs = $manager->retornarFilaExecucaoJob(str_replace(' ', '', $args['dataHoraInicio']), str_replace(' ', '', $args['dataHoraFim']));

        return $response->withJson($jobs, 200);
    } catch (\Exception $e) {
        $response->withJson(['erro' => $e->getMessage()], 500);
    }
});

$app->post('/adicionar-job', function (Request $request, Response $response) {
    $parsedBody = $request->getParsedBody();

    try {
        $job = new Job($parsedBody['id'], $parsedBody['descricao'], $parsedBody['dataMaximaConclusao'], $parsedBody['tempoEstimado']);
        $jobStorageFile = new JobStorageFile(__DIR__ . '/job.txt');
        $manager = new JobsManager($jobStorageFile);
        $result = $manager->adicionarJob($job);
        $response->withJson($result->toArray(), 200);

    } catch (\Exception $e) {
        $response->withJson(['erro' => $e->getMessage()], 500);
    }
});

$app->post('/excluir-jobs', function (Request $request, Response $response) {

    try {
        $jobStorageFile = new JobStorageFile(__DIR__ . '/job.txt');
        $jobStorageFile->excluirJobs();

        $response->withJson(['message' => 'Jobs exluidos com sucesso!'], 200);

    } catch (\Exception $e) {
        $response->withJson(['erro' => $e->getMessage()], 500);
    }
});

$app->run();
