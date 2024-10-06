<?php

namespace App\Controllers;

use App\Exceptions\InvalidOrderFormatException;
use App\Exceptions\InvalidOrderTransformerFactoryFormatException;
use App\Factories\OrderTransformerFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DiscountsController
{
	public function calculate(
		Request $request,
		Response $response,
	) : Response {
		// get the required information from request
		$requestHeader = $request->getHeaders();
		$requestBody = (array)$request->getQueryParams();

		try {
			// init the required transformer
			$orderTransformer = OrderTransformerFactory::create($requestHeader["Content-Type"][0]);

			// apply the discount rules
			$order = $orderTransformer->requestToModel($requestBody["order"]);

			// build the response
			$response->getBody()->write(
				$orderTransformer->modelToResponse($order)
			);
		} catch (InvalidOrderFormatException $exception) {
			$response = $response->withStatus(400);
			$response->getBody()->write(
				json_encode([
					"error" => $exception->getMessage()
				])
			);
		} catch (InvalidOrderTransformerFactoryFormatException $exception) {
			$response = $response->withStatus(400);
			$response->getBody()->write(
				json_encode([
					"error" => sprintf(
						"Invalid value '%s' of Header's Content-Type field. Accepted values are: %s",
						$requestHeader["Content-Type"][0],
						implode(",", $exception->getSupportedFormats())
					)
				])
			);
		} catch (\Throwable | \Exception $exception) {
			$response = $response->withStatus(500);
			$response->getBody()->write("Something went wrong. Please try again.");
		}

		return $response;
	}
}