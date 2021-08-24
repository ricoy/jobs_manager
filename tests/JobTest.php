<?php

use PHPUnit\Framework\TestCase;

use JobScheduler\Job;

final class JobTest extends TestCase
{
    public function testParametrizacaoJob()
    {
        $job = new Job(1, 'Job 1', '2021-08-24 08:00:00', 2);
        $this->assertEquals(1, $job->getId());
        $this->assertEquals('Job 1', $job->getDescricao());
        $this->assertEquals('2021-08-24 08:00:00', $job->getDataMaximaConclusao());
        $this->assertEquals(2, $job->getTempoEstimado());
    }
}