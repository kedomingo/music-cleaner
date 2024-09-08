<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Throwable;

abstract class LastFmApi {

  private const BASE_URL = 'https://ws.audioscrobbler.com/2.0';
  private Client $client;

  /**
   * @param Client $client
   */
  public function __construct(Client $client) {
    $this->client = $client;
  }

  protected abstract function getEndpoint(): string;

  protected function get($params) {
    $params = array_replace($params, [
        'format'  => 'json',
        'api_key' => $_SERVER['LASTFM_API_KEY'] ?? '',
        'method' => $this->getEndpoint()
    ]);

    echo 'URL: ' . self::BASE_URL . '?' . http_build_query($params) . "\n";

    try {
      $response = $this->client->get(self::BASE_URL, ['query' => $params]);

      return $response->getBody()->getContents();
    } catch (Throwable $e) {
      if ($e instanceof RequestException && method_exists($e, 'getResponse')) {
        $response = $e->getResponse();
        echo $response->getBody()->getContents();
      }
      throw $e;
    }
  }

}
