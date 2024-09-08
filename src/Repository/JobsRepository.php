<?php
declare(strict_types=1);

namespace MusicCleaner\Repository;

use MusicCleaner\Dao\JobsDao;
use MusicCleaner\Model\Job;

class JobsRepository {
  private JobsDao $dao;

  public function __construct(JobsDao $dao) {
    $this->dao = $dao;
  }

  public function findByPath(string $path): ?Job {
    $record = $this->dao->findByPath($path);
    if (!empty($record)) {
      return $this->arrayToJob($record);
    }

    return null;
  }

  public function findByNewPath(string $path): ?Job {
    $record = $this->dao->findByNewPath($path);
    if (!empty($record)) {
      return $this->arrayToJob($record);
    }

    return null;
  }

  public function enqueuePath(string $path): void {
    $this->dao->enqueuePath($path);
  }

  /**
   * @param int $howMany
   *
   * @return Job[]
   */
  public function dequeue(int $howMany): array {
    $records = $this->dao->getUnprocessedJobs($howMany);

    return array_map(function (array $record): Job {
      return $this->arrayToJob($record);
    }, $records);
  }

  public function getFilesToClean(int $howMany): array {
    $records = $this->dao->getJobsPendingClean($howMany);

    return array_map(function (array $record): Job {
      return $this->arrayToJob($record);
    }, $records);
  }

  private function arrayToJob(array $record): Job {
    return new Job($record['path'], (bool)$record['is_processed'], $record['id'], $record['new_path']);
  }

  public function markAsProcessed(int $id): void {
    $this->dao->markAsProcessed($id);
  }

  public function updateNewPathAndTags(?int $id, string $newPath, string $tags): void {
    $this->dao->updateNewPathAndTags($id, $newPath, $tags);
  }
}
