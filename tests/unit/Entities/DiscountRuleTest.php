<?php

namespace Tests\Entities;

use App\Entities\DiscountRule;
use PHPUnit\Framework\TestCase;

/**
 * @group entities
 * @group unit-test
 */
class DiscountRuleTest extends TestCase
{
	/**
	 * @dataProvider discountRulesList
	 * @param array $discountRuleAsArray
	 */
	public function testGetters(array $discountRuleAsArray)
	{
		$discountRule = new DiscountRule(
			$discountRuleAsArray["id"],
			$discountRuleAsArray["type"],
			$discountRuleAsArray["discount-rules"],
		);

		$this->assertEquals($discountRuleAsArray["id"], $discountRule->getId());
		$this->assertEquals($discountRuleAsArray["type"], $discountRule->getType());
		$this->assertEquals($discountRuleAsArray["discount-rules"], $discountRule->getDiscountRules());
	}

	public static function discountRulesList(): iterable
	{
		// this test data are extracted from all 3 orders samples found in folder 'example-orders'
		return [
			[
				[
					"id" => "1",
					"type" => "CustomerRevenueOver",
					"discount-rules" => [
						"revenue-over" => "1000",
						"percentage" => "10%",
    				],
				],
			],
			[
				[
					"id" => "2",
					"type" => "FreeItemOnQuantity",
					"discount-rules" => [
						"product-category-id" => "2",
						"quantity-criterion" => "5",
						"free-quantity" => "1",
    				],
				],
			],
			[
				[
					"id" => "3",
					"type" => "CheapestProductOfCategory",
					"discount-rules" => [
						"categoryId" => "1",
					  	"minimum_distinct_products" => "2",
					  	"percentage" => "20%",
					]
				],
			],
		];
	}
}
