<?php

use MusicCleaner\Dao\Connection;
use MusicCleaner\Service\Id3\Id3Getter;
use MusicCleaner\Service\Id3\Id3Writer;
use function DI\autowire;
use function DI\create;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new DI\Container();
$builder = new DI\ContainerBuilder();

$builder->addDefinitions([
     Id3Getter::class => autowire(Id3Getter::class)
         ->constructorParameter('wrapped', create(getID3::class)),
     Id3Writer::class => autowire(Id3Writer::class)
         ->constructorParameter('wrapped', create(getid3_writetags::class)),
     Connection::class => autowire(Connection::class)
         ->constructorParameter('sqliteFile', '/data/cleaner.sqlite')
]);

$container = $builder->build();

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__.'/../.env');

if (!function_exists('get_container')) {
  function get_container() {
    global $container;
    return $container;
  }
}
