<?php

namespace Tests\DiscountRules;

use App\DiscountRules\FreeItemOnQuantity;
use App\Models\Order;
use App\Models\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * @group discount-rules
 * @group unit-test
 */
class FreeItemOnQuantityTest extends TestCase
{
	public function testRuleIsApplicable() {
		$discountRule = new FreeItemOnQuantity(
			2,
			5,
			1,
			["B101", "B102", "B103"],
		);

		$orderSample = json_decode(file_get_contents("./example-orders/order1.json"), true);

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

		$discountRule->applyDiscount($order);
		$this->assertEquals(
			2,
			$order->getItems()[0]->getFreeQuantity()
		);
	}

	public function testRuleIsNotApplicable() {
		$discountRule = new FreeItemOnQuantity(
			2,
			5,
			1,
			["B101", "B102", "B103"],
		);

		$orderSample = json_decode(file_get_contents("./example-orders/order3.json"), true);

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

		$discountRule->applyDiscount($order);
		$this->assertEquals(
			0,
			$order->getItems()[0]->getFreeQuantity()
		);
		$this->assertEquals(
			0,
			$order->getItems()[1]->getFreeQuantity()
		);
	}
}
