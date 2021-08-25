<?php

use PHPUnit\Framework\TestCase;

use JobsManager\Job;
use JobsManager\JobsManager;
use JobsManager\iStorage;

final class JobsManagerTest extends TestCase
{
    public function testVerificaRetornoEsperadoAoAdicionarJob()
    {
        $job = new Job(1, 'Job 1', '2021-08-24 08:00:00', 2);

        $stub = $this->createMock(iStorage::class);

        $stub->method('salvarJob')
             ->willReturn($job);
        
        $jobsManager = new JobsManager($stub);
        $createdJob = $jobsManager->adicionarJob($job);
        $this->assertInstanceOf(Job::class, $createdJob);
    }

    /**
     * Este teste valida o resultado esperado conforme a proposta
     * do problema
     *
     * @return void
     */
    public function testVerificaRetornoFilaExecucaoJob()
    {
        $stub = $this->createMock(iStorage::class);

        $jobs = [
            new Job(1, 'Importação de arquivos de fundos', '2019-11-10 12:00:00', 2),
            new Job(2, 'Importação de dados da Base Legada', '2019-11-11 12:00:00', 4),
            new Job(3, 'Importação de dados de integração', '2019-11-11 08:00:00', 6)
        ];

        $stub->method('retornarJobs')
             ->willReturn($jobs);
        
        $jobsManager = new JobsManager($stub);
        $filaJobs = $jobsManager->retornarFilaExecucaoJob('2019-11-10 12:00:00', '2019-11-11 12:00:00');

        $resultadoEsperado = '[[1,3],[2]]';

        $this->assertEquals($resultadoEsperado, json_encode($filaJobs));
    }

    public function testVerificaFiltroDataRetornoFilaExecucaoJob()
    {
        $stub = $this->createMock(iStorage::class);

        $jobs = [
            new Job(1, 'Importação de arquivos de fundos', '2019-11-10 12:00:00', 2),
            new Job(2, 'Importação de dados da Base Legada', '2019-11-11 12:00:00', 4),
            new Job(3, 'Importação de dados de integração', '2019-11-11 08:00:00', 6)
        ];

        $stub->method('retornarJobs')
             ->willReturn($jobs);
        
        $jobsManager = new JobsManager($stub);
        $filaJobs = $jobsManager->retornarFilaExecucaoJob('2019-11-10 12:00:00', '2019-11-11 07:00:00');

        $resultadoEsperado = '[[1]]';

        $this->assertEquals($resultadoEsperado, json_encode($filaJobs));

        $filaJobs2 = $jobsManager->retornarFilaExecucaoJob('2019-11-10 12:00:00', '2019-11-11 08:00:00');

        $resultadoEsperado2 = '[[1,3]]';

        $this->assertEquals($resultadoEsperado2, json_encode($filaJobs2));

        $filaJobs3 = $jobsManager->retornarFilaExecucaoJob('2022-11-10 12:00:00', '2022-11-11 08:00:00');

        $resultadoEsperado3 = '[]';

        $this->assertEquals($resultadoEsperado3, json_encode($filaJobs3));
    }

    public function testValicaoDataInicioFimFilaExecucaoJob()
    {
        try {
            $stub = $this->createMock(iStorage::class);
            $jobsManager = new JobsManager($stub);
            $jobsManager->retornarFilaExecucaoJob('abc', date('Y-m-d H:i:s'));
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }

        try {
            $stub = $this->createMock(iStorage::class);
            $jobsManager = new JobsManager($stub);
            $jobsManager->retornarFilaExecucaoJob(date('Y-m-d H:i:s'), 'dfg');
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }
}