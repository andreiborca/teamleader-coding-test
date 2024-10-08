<?php

namespace Tests\DiscountRules;

use App\DiscountRules\CheapestProductOfCategory;
use App\Models\Order;
use App\Models\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * @group discount-rules
 * @group unit-test
 */
class CheapestProductOfCategoryTest extends TestCase
{
	public function testRuleIsApplicable() {
		$discountRule = new CheapestProductOfCategory(
			1,
			"20%",
			[
				[
					"id" => "A101",
					"price" => 9.75,
				],
				[
					"id" => "A102",
					"price" => 49.50,
				],
			],
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
		$this->assertNotEmpty($order->getItems()[0]->getDiscounts());
		$this->assertEmpty($order->getItems()[1]->getDiscounts());
	}

	public function testRuleNotIsApplicable() {
		$discountRule = new CheapestProductOfCategory(
			1,
			"20%",
			[
				[
					"id" => "A101",
					"price" => 9.75,
				],
				[
					"id" => "A102",
					"price" => 49.50,
				],
			],
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
		$this->assertEmpty($order->getItems()[0]->getDiscounts());
	}

	public function testRuleNotIsApplicableEvenThoughProductIsFromCategory() {
		$discountRule = new CheapestProductOfCategory(
			1,
			"20%",
			[
				[
					"id" => "A101",
					"price" => 9.75,
				],
				[
					"id" => "A102",
					"price" => 49.50,
				],
			],
		);

		$orderSample = json_decode(file_get_contents("./example-orders/order1.json"), true);

		$orderItems[] = new OrderItem(
			$orderSample["items"][0]["product-id"],
			$orderSample["items"][0]["quantity"],
			$orderSample["items"][0]["unit-price"],
			$orderSample["items"][0]["total"],
		);

		$order = new Order(
			$orderSample["id"],
			$orderSample["customer-id"],
			$orderItems,
			$orderSample["total"]
		);

		$discountRule->applyDiscount($order);
		$this->assertEmpty($order->getItems()[0]->getDiscounts());
	}
}
