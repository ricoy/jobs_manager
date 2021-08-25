<?php

namespace JobsManager;

use JobsManager\Job;

interface iStorage
{
    public function salvarJob(Job $job);
    public function retornarJobs();
    public function excluirJobs();
}