<?php
declare(strict_types=1);

namespace MusicCleaner\Service;

use MusicCleaner\Model\Job;
use MusicCleaner\Model\SearchTerm;

class Mp3Cleaner {

  private JobsService $jobsService;

  public function __construct(JobsService $jobsService) {
    $this->jobsService = $jobsService;
  }

  public function clean(int $howMany) {
    $jobs = $this->jobsService->getFilesToClean($howMany);
    foreach ($jobs as $job) {
      $this->processJob($job);
    }
  }

  private function processJob(Job $job) {

    $this->moveFile($job);
  }

  private function moveFile(Job $job) {
    $oldPath   = $job->getPath();
    $extension = strtolower(preg_replace('/^.*\.(\w+)$/', '$1', $oldPath));
    $newPath   = $job->getNewPath() . '.' . $extension;

    echo "\n\n$oldPath -> $newPath\n";
    if (!file_exists($oldPath)) {
      echo "THE SOURCE FILE DOES NOT EXIST\n";
      return;
    }

    @mkdir(dirname($newPath), 0777, true);
    rename($oldPath, $newPath);
    $this->jobsService->markAsProcessed($job->getId());
  }
}
