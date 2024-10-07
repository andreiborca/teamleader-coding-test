<?php

namespace App\Controllers;

use App\DiscountRules\CustomerRevenueOver;
use App\DiscountRules\FreeItemOnQuantity;
use App\Exceptions\InvalidOrderFormatException;
use App\Exceptions\InvalidOrderTransformerFactoryFormatException;
use App\Factories\OrderTransformerFactory;
use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\DiscountRuleInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductsRepository;
use App\Services\DiscountsApplierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DiscountsController
{
	private CustomerRepositoryInterface $customerRepository;

	/**
	 * @var DiscountRuleInterface[]
	 */
	private array $discountRules;
	private DiscountsApplierService $discountApplierService;

	public function __construct()
	{
		// TODO: inject the customer repository through service container.
		$this->customerRepository = new CustomerRepository();
		// TODO: inject the product repository through service container.
		$productRepository = new ProductsRepository();

		$freeItemsCategoryId = 2;
		$this->discountRules["product"][] = new FreeItemOnQuantity(
			$freeItemsCategoryId,
			5,
			1,
			$productRepository->getAllProductsIdFromCategory($freeItemsCategoryId),
		);

		$this->discountRules["order"][] = new CustomerRevenueOver(1000, "10%");

		$this->discountApplierService = new DiscountsApplierService($this->discountRules);
	}

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
			$customer = $this->customerRepository->findCustomerById($order->getCustomerId());
			$this->discountApplierService->apply($order, $customer);

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