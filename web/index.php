<?php
use Symfony\Component\HttpFoundation\Request;

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/search', function(Request $request) use($app, $data) {
  // GET params
  $params = $request->query->all();

  return $app['twig']->render('search.twig', array(
    'to' => $params['to'],
    'from' => $params['from'],
    'deptdate' => DateTime::createFromFormat('d/m/Y', $params['deptdate']),
    'rtndate' => DateTime::createFromFormat('d/m/Y', $params['rtndate']),
    'adults' => $params['adults'],
    'children' => $params['children'],
    'infants' => $params['infants'],
    'journey_type' => $params['journey_type'],
    'data' => $data));
});

$app->run();
