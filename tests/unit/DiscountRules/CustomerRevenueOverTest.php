<?php

namespace Tests\DiscountRules;

use App\DiscountRules\CustomerRevenueOver;
use App\Entities\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @group discount-rules
 * @group unit-test
 */
class CustomerRevenueOverTest extends TestCase
{
	/**
	 * @dataProvider dataSets
	 *
	 * @param Order $order
	 * @param Customer $customer
	 *
	 * @throws Exception
	 */
	public function testRuleIsApplicable(Order $order, Customer $customer) {
		$discountRule = new CustomerRevenueOver(500.00, "10%");
		$discountRule->applyCustomerDiscount($order, $customer);
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
	public function testRuleIsNotApplicable(Order $order, Customer $customer) {
		$discountRule = new CustomerRevenueOver(5000.00, "10%");
		$discountRule->applyCustomerDiscount($order, $customer);
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
	public function testExceptionIsRaised(Order $order, Customer $customer) {
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Missing customer data. Use method CustomerRevenueOver::applyCustomerDiscount");

		$discountRule = new CustomerRevenueOver(500.00, "10%");
		$discountRule->applyDiscount($order);
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
