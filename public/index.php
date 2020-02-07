<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

/**
 * Add Error Handling Middleware
 *
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.

 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(displayErrorDetails(), true, true);

// Define app routes
$app->get('/', function (Request $request, Response $response, $args) {
  $response->getBody()->write("Hello world!");
  return $response;
});

// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
  $name = $args['name'];
  $response->getBody()->write("Hello, $name");
  return $response;
});

// Run app
$app->run();

function displayErrorDetails() {
  return !isProduction() || debugMode();
}
function isProduction() {
  return in_array(getenv('APP_ENV'), ['prod', 'production']);
}
function debugMode() {
  return queryArgIsTruthy('displayErrorDetails');
}
function queryArgIsTruthy($arg) {
  return isset($_GET[$arg]) && $_GET[$arg];
}
