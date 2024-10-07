<?php

namespace Tests\Services;

use App\DiscountRules\CustomerRevenueOver;
use App\DiscountRules\FreeItemOnQuantity;
use App\Entities\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\DiscountsApplierService;
use PHPUnit\Framework\TestCase;

/**
 * @group discounts-service
 * @group functional-test
 */
class DiscountsApplierServiceTest extends TestCase
{
	/**
	 * @dataProvider dataSets
	 *
	 * @param Order $order
	 * @param Customer $customer
	 *
	 * @throws Exception
	 */
	public function testCustomerRevenueOverIsApplicable(Order $order, Customer $customer) {
		$discountRule = new CustomerRevenueOver(500.00, "10%");
		$discountsService = new DiscountsApplierService([
			"order" => [$discountRule]
		]);

		$order = $discountsService->apply($order, $customer);

		$this->assertEquals(["percentage" => "10%"], $order->getDiscounts());

	}

	/**
	 * @dataProvider dataSets
	 *
	 * @param Order $order
	 * @param Customer $customer
	 *
	 * @throws Exception
	 */
	public function testCustomerRevenueOverIsNotApplicable(Order $order, Customer $customer) {
		$discountRule = new CustomerRevenueOver(5000.00, "10%");
		$discountsService = new DiscountsApplierService([
			"order" => [$discountRule]
		]);

		$order = $discountsService->apply($order, $customer);

		$this->assertEmpty($order->getDiscounts());
	}

	/**
	 * @dataProvider dataSets
	 *
	 * @param Order $order
	 * @param Customer $customer
	 *
	 * @throws Exception
	 */
	public function testFreeItemOnQuantityIsApplicable(Order $order, Customer $customer) {
		$discountRule = new FreeItemOnQuantity(
			2,
			5,
			1,
			["B101", "B102", "B103"],
		);
		$discountsService = new DiscountsApplierService([
			"product" => [$discountRule]
		]);

		$order = $discountsService->apply($order, $customer);

		$this->assertEquals(
			2,
			$order->getItems()[0]->getFreeQuantity(),
		);
	}

	/**
	 * @dataProvider dataSets
	 *
	 * @param Order $order
	 * @param Customer $customer
	 *
	 * @throws Exception
	 */
	public function testFreeItemOnQuantityIsNotApplicable(Order $order, Customer $customer) {
		$discountRule = new FreeItemOnQuantity(
			1,
			5,
			1,
			["A101", "A102"],
		);
		$discountsService = new DiscountsApplierService([
			"product" => [$discountRule]
		]);

		$order = $discountsService->apply($order, $customer);

		$this->assertEquals(
			0,
			$order->getItems()[0]->getFreeQuantity(),
		);
	}

	public static function dataSets() : iterable
	{
		return [
			[
				new Order(
					1,
					1,
					[
						new OrderItem("B102", 10, 4.99, 49.90)
					],
					49.90
				),
				new Customer(
					1,
					"Test Customer",
					new \DateTime(),
					1050.00
				)
			]
		];
	}
}
