<?php

namespace JobsManager;

use JobsManager\Job;
use JobsManager\iStorage;

/**
 * Classe responsavel pelo gerenciamento de Jobs
 */
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
        return $this->storage->salvarJob($job);
    }

    private function retornarJobsPorPeriodo()
    {
        
        $dataHoraInicio = $this->dataHoraInicio; 
        $dataHoraFim = $this->dataHoraFim;

        $listaJobs = $this->storage->retornarJobs();

        $filtrarPeriodo = function($job) use ($dataHoraInicio, $dataHoraFim) {
            if (strtotime($job->getDataMaximaConclusao()) >= strtotime($dataHoraInicio) && 
                strtotime($job->getDataMaximaConclusao()) <= strtotime($dataHoraFim)) {
                return $job;
            }

            return false;
        };

        return array_filter($listaJobs, $filtrarPeriodo);
    }

    public function retornarFilaExecucaoJob($dataHoraInicio, $dataHoraFim)
    {
        if (strtotime($dataHoraInicio) === false || strtotime($dataHoraFim) === false) {
            throw new \InvalidArgumentException("O formato de data/hora deve ser yyyy-mm-ddThh:mm:ss");
        }
        
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

        // enquanto nao tiver alocado  os jobs crio novas sequencias
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
