<?php

namespace Tests\Models;

use App\Models\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * @group models
 * @group unit-test
 */
class OrderItemTest extends TestCase
{
	/**
	 * @dataProvider orderItemList
	 * @param array $orderItemAsArray
	 */
	public function testGetters(array $orderItemAsArray)
	{
		$orderItem = new OrderItem(
			$orderItemAsArray["product-id"],
			$orderItemAsArray["quantity"],
			$orderItemAsArray["unit-price"],
			$orderItemAsArray["total"],
		);

		$this->assertEquals($orderItemAsArray["product-id"], $orderItem->getProductId());
		$this->assertEquals($orderItemAsArray["quantity"], $orderItem->getQuantity());
		$this->assertEquals($orderItemAsArray["unit-price"], $orderItem->getUnitPrice());
		$this->assertEquals($orderItemAsArray["total"], $orderItem->getTotal());
	}

	/**
	 * @dataProvider orderItemList
	 * @param array $orderItemAsArray
	 */
	public function testQuantitySetter(array $orderItemAsArray)
	{
		$orderItem = new OrderItem(
			$orderItemAsArray["product-id"],
			$orderItemAsArray["quantity"],
			$orderItemAsArray["unit-price"],
			$orderItemAsArray["total"],
		);

		// the value 3 was chosen as it is not fount in the data provider
		$orderItem->setQuantity(3);

		$this->assertEquals(3, $orderItem->getQuantity());
		$this->assertEquals(3 * $orderItemAsArray["unit-price"], $orderItem->getTotal());
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

