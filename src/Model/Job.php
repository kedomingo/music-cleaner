<?php
declare(strict_types=1);

namespace MusicCleaner\Model;

class Job
{
  private ?int $id;
  private string $path;
  private ?string $newPath;
  private bool $isProcessed;

  public function __construct(string $path, bool $isProcessed = false, ?int $id = null, ?string $newPath = null)
  {
    $this->id = $id;
    $this->path = $path;
    $this->newPath = $newPath;
    $this->isProcessed = $isProcessed;
  }

  /**
   * @return int|null
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getPath(): string {
    return $this->path;
  }

  /**
   * @return string|null
   */
  public function getNewPath(): ?string {
    return $this->newPath;
  }

  /**
   * @return bool
   */
  public function isProcessed(): bool {
    return $this->isProcessed;
  }
}
