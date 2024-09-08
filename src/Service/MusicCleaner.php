<?php
declare(strict_types=1);

namespace MusicCleaner\Service;

class MusicCleaner {

  private FileScanner $fileScanner;
  private SearchTermBuilder $searchTermBuilder;
  private JobsService $jobsService;
  private QueueProcessor $queueProcessor;
  private Mp3Cleaner $mp3Cleaner;

  public function __construct(
      FileScanner       $fileScanner,
      SearchTermBuilder $searchTermBuilder,
      JobsService       $jobsService,
      QueueProcessor    $queueProcessor,
      Mp3Cleaner        $mp3Cleaner
  ) {
    $this->fileScanner       = $fileScanner;
    $this->searchTermBuilder = $searchTermBuilder;
    $this->jobsService       = $jobsService;
    $this->queueProcessor    = $queueProcessor;
    $this->mp3Cleaner        = $mp3Cleaner;
  }

  public function clean(string $dir) {
    $files = $this->fileScanner->listFiles($dir);
    $i = 0;
    foreach ($files as $filePath) {
      $this->queue($filePath);
    }
    $this->queueProcessor->process(900);
//    $this->mp3Cleaner->clean(2000);
    echo "Done";
    exit;
  }

  private function queue($filePath): void {
    if (!$this->isSupported($filePath)) {
      return;
    }

    $this->jobsService->enqueuePath($filePath);
  }

  private function isSupported(string $filePath) {
    $supportedFiles = ['flac' => 'flac', 'mp3' => 'mp3', 'wav' => 'wav'];
    $extension      = strtolower(preg_replace('/(.*)\.(\w+)$/', '$2', $filePath));

    return array_key_exists($extension, $supportedFiles);
  }

}
