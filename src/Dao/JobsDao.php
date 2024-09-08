<?php
declare(strict_types=1);

namespace MusicCleaner\Dao;

class JobsDao {
  private Connection $db;

  public function __construct(Connection $db) {
    $this->db = $db;
  }

  public function findByPath(string $path): array {
    $path      = strtolower($path);
    $sql       = 'SELECT * FROM jobs WHERE path = :path AND is_processed = 0 LIMIT 1';
    $statement = $this->db->prepare($sql);
    $statement->bindValue('path', $path);
    $result = $statement->execute();

    return $result->fetchArray() ?: [];
  }

  public function findByNewPath(string $path): array {
    $path      = strtolower($path);
    $sql       = 'SELECT * FROM jobs WHERE new_path = :path AND is_processed = 1 LIMIT 1';
    $statement = $this->db->prepare($sql);
    $statement->bindValue('path', $path);
    $result = $statement->execute();

    return $result->fetchArray() ?: [];
  }

  public function enqueuePath(string $path): void {
    $sql       = 'INSERT INTO jobs(path, is_processed) VALUES (:path, 0)';
    $statement = $this->db->prepare($sql);
    $statement->bindValue('path', strtolower($path));
    $statement->execute();
  }

  public function getUnprocessedJobs(int $howMany) {
    $sql       = 'SELECT * FROM jobs WHERE is_processed = 0 AND new_path IS NULL LIMIT :limit';
    $statement = $this->db->prepare($sql);
    $statement->bindValue('limit', $howMany);
    $result = $statement->execute();

    $rows = [];
    while ($row = $result->fetchArray()) {
      $rows[] = $row;
    }

    return $rows;
  }

  public function getJobsPendingClean(int $howMany) {

    $sql       = 'SELECT * FROM jobs WHERE is_processed = 0 AND new_path IS NOT NULL LIMIT :limit';
    $statement = $this->db->prepare($sql);
    $statement->bindValue('limit', $howMany);
    $result = $statement->execute();

    $rows = [];
    while ($row = $result->fetchArray()) {
      $rows[] = $row;
    }

    return $rows;
  }

  public function markAsProcessed(int $id): void {
    $sql       = 'UPDATE jobs SET is_processed = 1 WHERE id = :id';
    $statement = $this->db->prepare($sql);
    $statement->bindValue('id', $id);
    $statement->execute();
  }

  public function updateNewPathAndTags(?int $id, string $newPath, string $tags) {
    $sql       = 'UPDATE jobs SET new_path = :newPath, tags = :tags WHERE id = :id';
    $statement = $this->db->prepare($sql);
    $statement->bindValue('newPath', $newPath);
    $statement->bindValue('tags', $tags);
    $statement->bindValue('id', $id);
    $statement->execute();
  }
}
