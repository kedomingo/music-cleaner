<?php
declare(strict_types=1);

namespace MusicCleaner\Dao;

use Exception;
use SQLite3;

class Connection
{
  private $db;

  public function __construct(string $sqliteFile)
  {
    try {
      $this->db = new SQLite3($sqliteFile);
    } catch (Exception $e) {
      die("Error: " . $e->getMessage());
    }
  }

  public function query($sql)
  {
    return $this->db->query($sql);
  }

  public function prepare($sql)
  {
    return $this->db->prepare($sql);
  }

  public function close()
  {
    $this->db->close();
  }
}
