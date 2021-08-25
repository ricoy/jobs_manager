<?php

namespace JobsManager;

use JobsManager\Job;
use JobsManager\iStorage;

class JobsManager {

    private $dataHoraInicio;
    private $dataHoraFim;

    const TEMPO_MAXIMO_SEQUENCIA_JOBS = 8;

    public function __construct(iStorage $storage)
    {
        $this->storage = $storage;
    }

    public function adicionarJob(Job $job)
    {
        $this->storage->salvarJob($job);
    }

    public function retornarTodosJobs()
    {
        return $this->storage->retornarJobs();
    }

    private function retornarJobsPorPeriodo()
    {
        
        $dataHoraInicio = $this->dataHoraInicio; 
        $dataHoraFim = $this->dataHoraFim;

        $listaJobs = $this->retornarTodosJobs();

        $filtrarPeriodo = function($job) use ($dataHoraInicio, $dataHoraFim) {
            if (strtotime($job->getDataMaximaConclusao()) >= strtotime($dataHoraInicio) && 
                strtotime($job->getDataMaximaConclusao()) <= strtotime($dataHoraFim)) {
                return $job;
            }

            return false;
        };

        return array_filter($listaJobs, $filtrarPeriodo);
    }

    public function retornarJobsEmOrdemDeExecucao($dataHoraInicio, $dataHoraFim)
    {
        $this->dataHoraInicio = $dataHoraInicio;
        $this->dataHoraFim = $dataHoraFim;
        
        $jobsDoPeriodo = $this->retornarJobsPorPeriodo();

        $tempoEstimadoJobs = [];
        $dataMaximaConclusaoJobs = [];

        foreach ($jobsDoPeriodo as $job) {
            $tempoEstimadoJobs[$job->getId()] = $job->getTempoEstimado();
            $dataMaximaConclusaoJobs[$job->getId()] = strtotime($job->getDataMaximaConclusao());
        }

        $listaSequenciaJobs = [];
        
        // ordeno os jobs por prioridade, ou seja, por menor data maxima de conclusao
        asort($dataMaximaConclusaoJobs);

        // enquanto nao tiver alocado todos os jobs crio novas sequencias
        while (!empty($dataMaximaConclusaoJobs)) {
            $sequenciaJob = [];
            $tempoTotalSequencia = 0;

            // percorro cada job priorizado para verificar se posso alocar na sequencia
            foreach (array_keys($dataMaximaConclusaoJobs) as $idJob) {
                if (($tempoTotalSequencia + $tempoEstimadoJobs[$idJob]) <= self::TEMPO_MAXIMO_SEQUENCIA_JOBS) {
                    $sequenciaJob[] = $idJob;
                    $tempoTotalSequencia += $tempoEstimadoJobs[$idJob];
                    
                    // se ja aloquei removo da lista 
                    unset($dataMaximaConclusaoJobs[$idJob]);
                }
            }

            // adiciono a sequencia na lista de retorno
            $listaSequenciaJobs[] = $sequenciaJob;
        }
       
        return $listaSequenciaJobs;
    }
}
