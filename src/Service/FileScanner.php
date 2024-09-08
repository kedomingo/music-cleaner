<?php
declare(strict_types=1);

namespace MusicCleaner\Service;

class FileScanner {

  public function listFiles(string $dir):array {
    if (!is_dir($dir)) {
      throw new \Exception("The given directory $dir is not valid");
    }
    return $this->listFilesRecursive($dir);
  }

  protected function listFilesRecursive($dir) {
    $files = [];

    if (is_dir($dir)) {
      $dh = opendir($dir);

      while (($file = readdir($dh)) !== false) {
        if ($file != '.' && $file != '..') {
          $path = $dir . '/' . $file;
          if (is_dir($path)) {
            $files = array_merge($files, $this->listFilesRecursive($path));
          } else {
            $files[] = $path;
          }
        }
      }

      closedir($dh);
    }

    return $files;
  }
}
