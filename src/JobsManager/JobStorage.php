<?php

namespace JobsManager;

use JobsManager\Job;
use JobsManager\iStorage;

class JobStorage  {

    public function salvarJob(iStorage $storage, Job $job)
    {
        return $storage->salvarJob($job);
    }
}