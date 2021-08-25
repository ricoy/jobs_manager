<?php

namespace JobsManager;

use JobsManager\Job;

class JobsManager {

    private $jobs = [];
    
    public function adicionarJob(Job $job)
    {
        $this->jobs[] = $job;
    }

    public function retornarTodosJobs()
    {
        return $this->jobs;
    }
    
    public function retornarJobsPorJanelaExecucao($dataHoraInicio, $dataHoraFim)
    {
        
    }

    public function executarJob(Job $job)
    {

    }

    public function executarFilaDeJobs($dataHoraInicio, $dataHoraFim)
    {

    }
}
