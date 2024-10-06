<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\DiscountsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
	$response->getBody()->write("Hello, Slim Framework on Docker!");
	return $response;
});

$app->post("/discounts/calculate", [DiscountsController::class, "calculate"]);

$app->run();