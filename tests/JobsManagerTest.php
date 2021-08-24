<?php

use PHPUnit\Framework\TestCase;

use JobScheduler\Job;
use JobScheduler\JobsManager;

final class JobsManagerTest extends TestCase
{
    public function testVerificaSeJobFoiAdicionado()
    {
        $job = new Job(1, 'Job 1', '2021-08-24 08:00:00', 2);
        
        $jobsManager = new JobsManager();
        $jobsManager->adicionarJob($job);

        $jobs = $jobsManager->retornarTodosJobs();
        
        $this->assertIsArray($jobs);
        $this->assertContainsOnlyInstancesOf(Job::class, $jobs);
        $this->assertCount(1, $jobs);
    }
}