<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use JobsManager\Job;
use JobsManager\JobsManager;
use JobsManager\JobStorageFile;

require_once  __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

$app = new \Slim\App();

$app->get('/listar-fila-jobs/{dataHoraInicio}/{dataHoraFim}', function (Request $request, Response $response, array $args) {

    try {
        $jobStorageFile = new JobStorageFile(__DIR__ . '/job.txt');
        $manager = new JobsManager($jobStorageFile);
        $jobs = $manager->retornarJobsEmOrdemDeExecucao(str_replace(' ', '', $args['dataHoraInicio']), str_replace(' ', '', $args['dataHoraFim']));

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


$app->post('/excluir-jobs', function (Response $response) {
    
    try {
        $jobStorageFile = new JobStorageFile(__DIR__ . '/job.txt');
        $jobStorageFile->excluirJobs();
        
        $response->withJson(['message' => 'Jobs exluidos com sucesso!'], 200);

    } catch (\Exception $e) {
        $response->withJson(['erro' => $e->getMessage()], 500);
    }
});

$app->run();