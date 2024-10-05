<?php

namespace Tests\Models;

use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @group models
 * @group unit-test
 */
class OrderTest extends TestCase
{
	/**
	 * @dataProvider orderSamples
	 * @param string $orderSampleLocation
	 */
	public function testGetters(string $orderSampleLocation)
	{
		$orderSample = json_decode(file_get_contents($orderSampleLocation), true);

		$orderItems = [];
		foreach ($orderSample["items"] as $item) {
			$orderItems[] = new OrderItem(
				$item["product-id"],
				$item["quantity"],
				$item["unit-price"],
				$item["total"],
			);
		}

		$order = new Order(
			$orderSample["id"],
			$orderSample["customer-id"],
			$orderItems,
			$orderSample["total"]
		);

		$this->assertEquals($orderSample["id"], $order->getId());
		$this->assertEquals($orderSample["customer-id"], $order->getCustomerId());
		$this->assertEquals($orderItems, $order->getItems());
		$this->assertEquals($orderSample["total"], $order->getTotal());
	}

	/**
	 * @dataProvider orderSamples
	 * @param string $orderSampleLocation
	 */
	public function testInvalidOrderItemType(string $orderSampleLocation)
	{
		$orderSample = json_decode(file_get_contents($orderSampleLocation), true);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage("All elements must be of type OrderItem");

		// check that indifferent which element is not of type OrderItem the Exception is raised
		// for testing purpose the invalid element will be always the last one.
		$orderItems = [];
		$count = count($orderSample["items"]);
		for ($i = 0; $i < $count; $i++) {
			if ($i == $count - 1) {
				$orderItems = $orderSample["items"][$i];
			} else {
				$orderItems[] = new OrderItem(
					$orderSample["items"][$i]["product-id"],
					$orderSample["items"][$i]["quantity"],
					$orderSample["items"][$i]["unit-price"],
					$orderSample["items"][$i]["total"],

				);
			}
		}

		new Order(
			$orderSample["id"],
			$orderSample["customer-id"],
			$orderItems,
			$orderSample["total"]
		);
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
