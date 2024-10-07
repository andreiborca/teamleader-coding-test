<?php

namespace Tests\Services;

use App\DiscountRules\CustomerRevenueOver;
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
