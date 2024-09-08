<?php
declare(strict_types=1);

namespace MusicCleaner\Service;

use MusicCleaner\Dao\JobsDao;
use MusicCleaner\Model\Job;
use MusicCleaner\Repository\JobsRepository;

class JobsService {
  private JobsRepository $repository;

  /**
   * @param JobsRepository $repository
   */
  public function __construct(JobsRepository $repository) {
    $this->repository = $repository;
  }

  public function findByPath(string $path): ?Job {
    $notYetProcessed = $this->repository->findByPath($path);
    if ($notYetProcessed !== null) {
      return $notYetProcessed;
    }
    return $this->repository->findByNewPath($path);
  }

  public function enqueuePath(string $path): void {
    echo "Checking $path \n";
    $existing = $this->findByPath($path);
    if ($existing !== null) {
      echo "$path is already in the DB\n";
      return;
    }
    echo "Enqueueing $path\n";
    $this->repository->enqueuePath($path);
  }

  /**
   * @param int $howMany
   *
   * @return Job[]
   */
  public function consume(int $howMany): array {
    return $this->repository->dequeue($howMany);
  }

  public function getFilesToClean(int $howMany) {
    return $this->repository->getFilesToClean($howMany);
  }
  public function markAsProcessed(int $id):void {
    $this->repository->markAsProcessed($id);
  }

  public function updatePathAndTags(Job $job, string $artist, string $album, string $song, string $tags) {

    // cleanup Artist. Remove collabs
    $song = str_replace('/', '+', $artist) .' - ' . $song;
    $artist = $this->cleanupArtist($artist);

    $oldPath = $job->getPath();
    $parts = explode(DIRECTORY_SEPARATOR, $oldPath);
    array_pop($parts);
    array_pop($parts);
    array_pop($parts);
    $parts[] = $artist;
    $parts[] = $album;
    $parts[] = $song;
    $newPath = implode(DIRECTORY_SEPARATOR, $parts);

    echo "New path suggested: $newPath\n";
    $this->repository->updateNewPathAndTags($job->getId(), $newPath, $tags);
  }

  private function cleanupArtist(string $artist) {
    if (!$this->isPossibleCollab($artist)) {
      return $artist;
    }

    $collaborator = $this->getFeaturing($artist) ?? $this->getCollab($artist);

    return str_replace($collaborator, '', $artist);
  }

  private function isPossibleCollab(string $str): bool {
    return $this->getFeaturing($str) !== null || $this->getCollab($str) !== null;
  }

  private function getFeaturing(string $str): ?string {
    if (preg_match('/ (f(ea)?t(\.|uring)?.*)/i', $str, $matches)) {
      return $matches[1];
    }
    return null;
  }

  private function getCollab(string $str): ?string {
    if (preg_match('/\w((\/|\+)\w.*)/', $str, $matches)) {
      return $matches[1];
    }
    return null;
  }
}
