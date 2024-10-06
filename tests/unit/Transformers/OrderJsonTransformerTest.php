<?php

namespace Tests\Transformers;

use App\Exceptions\InvalidOrderFormatException;
use App\Transformers\OrderItemTransformer;
use App\Transformers\OrderJsonTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @group transformers
 * @group unit-test
 */
class OrderJsonTransformerTest extends TestCase
{
	/**
	 * @dataProvider orderSamples
	 * @param string $orderSampleLocation
	 */
	public function testRequestToModel(string $orderSampleLocation)
	{
		$orderAsJson = file_get_contents($orderSampleLocation);
		$transformer = new OrderJsonTransformer(
			new OrderItemTransformer()
		);
		$order = $transformer->requestToModel($orderAsJson);

		$orderAsArray = json_decode($orderAsJson, true);

		$this->assertEquals($orderAsArray["id"], $order->getId());
		$this->assertEquals($orderAsArray["customer-id"], $order->getCustomerId());
		$this->assertEquals($orderAsArray["total"], $order->getTotal());
	}

	/**
	 * @dataProvider orderSamples
	 * @param string $orderSampleLocation
	 */
	public function testModelToRequest(string $orderSampleLocation) {
		$orderAsJson = str_replace(
			"\r\n",
			"",
			file_get_contents($orderSampleLocation),
		);
		$orderAsJson = str_replace(" ","", $orderAsJson);

		$transformer = new OrderJsonTransformer(
			new OrderItemTransformer()
		);
		$order = $transformer->requestToModel($orderAsJson);
		$orderAsString = $transformer->modelToResponse($order);

		$this->assertEquals($orderAsJson, $orderAsString);
	}

	/**
	 * @dataProvider orderSamples
	 * @param string $orderSampleLocation
	 */
	public function testInvalidOrderItemType(string $orderSampleLocation)
	{
		$orderAsJson = file_get_contents($orderSampleLocation);
		$transformer = new OrderJsonTransformer(
			new OrderItemTransformer()
		);

		$this->expectException(InvalidOrderFormatException::class);
		$transformer->requestToModel(substr($orderAsJson, 0, 10));
	}


	public static function orderSamples(): iterable
	{
		return [
			["./example-orders/order1.json"],
			["./example-orders/order2.json"],
			["./example-orders/order3.json"],
		];
	}
}
