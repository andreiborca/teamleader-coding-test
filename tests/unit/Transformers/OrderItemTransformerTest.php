<?php

namespace Tests\Transformers;

use App\Transformers\OrderItemTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @group transformers
 * @group unit-test
 */
class OrderItemTransformerTest extends TestCase
{
	/**
	 * @dataProvider orderItemList
	 * @param array $orderItemAsArray
	 */
	public function testRequestToModel(array $orderItemAsArray) {
		$transformer = new OrderItemTransformer();
		$orderItem = $transformer->requestToModel($orderItemAsArray);

		$this->assertEquals($orderItemAsArray["product-id"], $orderItem->getProductId());
		$this->assertEquals($orderItemAsArray["quantity"], $orderItem->getQuantity());
		$this->assertEquals($orderItemAsArray["unit-price"], $orderItem->getUnitPrice());
		$this->assertEquals($orderItemAsArray["total"], $orderItem->getTotal());
	}

	/**
	 * @dataProvider orderItemList
	 * @param array $orderItemAsArray
	 */
	public function testModelToRequest(array $orderItemAsArray) {
		$transformer = new OrderItemTransformer();
		$orderItem = $transformer->requestToModel($orderItemAsArray);
		$orderItem = $transformer->modelToResponse($orderItem);

		$this->assertEquals($orderItemAsArray["product-id"], $orderItem["product-id"]);
		$this->assertEquals($orderItemAsArray["product-id"], $orderItem["product-id"]);
		$this->assertEquals($orderItemAsArray["unit-price"], $orderItem["unit-price"]);
		$this->assertEquals($orderItemAsArray["total"], $orderItem["total"]);
	}

	public static function orderItemList(): iterable
	{
		// this test data are extracted from all 3 orders samples found in folder 'example-orders'
		return [
			[
				[
					"product-id" => "B102",
					"quantity" => 10,
					"unit-price" => 4.99,
					"total" => 49.90,
				],
			],
			[
				[
					"product-id" => "B102",
					"quantity" => 5,
					"unit-price" => 4.99,
					"total" => 24.95,
				],
			],
			[
				[
					"product-id" => "A101",
					"quantity" => 2,
					"unit-price" => 9.75,
					"total" => 19.50,
				],
			],
			[
				[
					"product-id" => "A102",
					"quantity" => 1,
					"unit-price" => 49.50,
					"total" => 49.50,
				],
			],
		];
	}
}
