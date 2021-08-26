<?php

namespace JobsManager;

use JobsManager\Job;
use JobsManager\iStorage;

class JobStorageFile implements iStorage {

    private $filePath;

    public function __construct($filePath)
    {
        if (!is_writable(dirname($filePath))) {
            throw new \InvalidArgumentException("O local de armazenamento de jobs {$filePath} não possui permissões de escrita.");
        }

        if (!file_exists($filePath)) {
            file_put_contents($filePath, json_encode([]));
        }
        
        $this->filePath = $filePath;
    }

    public function salvarJob(Job $job)
    {
        $jobsCadastrados = $this->retornarJobs();

        $listaJobs = [];
        foreach($jobsCadastrados as $jobCorrente) {
            $listaJobs[] = $jobCorrente->toArray();
        }

        $listaJobs[] = $job->toArray();
        
        file_put_contents($this->filePath, json_encode($listaJobs));
        return $job;
    }

    public function retornarJobs()
    {
        $listaJobs = json_decode(trim(file_get_contents($this->filePath)));

        $jobs = [];
        foreach ($listaJobs as $job) {
            $objJob = new Job($job->id, $job->descricao, $job->dataMaximaConclusao, $job->tempoEstimado);
            $jobs[] = $objJob;
        }

        return $jobs;
    }

    public function excluirJobs()
    {
        unlink($this->filePath);
    }
}