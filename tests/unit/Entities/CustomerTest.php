<?php

namespace Tests\Entities;

use App\Entities\Customer;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @group entities
 * @group unit-test
 */
class CustomerTest extends TestCase
{
	/**
	 * @dataProvider customerList
	 * @param array $customerAsArray
	 */
	public function testGetters(array $customerAsArray)
	{
		$customer = new Customer(
			$customerAsArray["id"],
			$customerAsArray["name"],
			new DateTime($customerAsArray["since"]),
			$customerAsArray["revenue"],
		);

		$this->assertEquals($customerAsArray["id"], $customer->getId());
		$this->assertEquals($customerAsArray["name"], $customer->getName());
		$this->assertEquals($customerAsArray["since"], $customer->getSince()->format("Y-m-d"));
		$this->assertEquals($customerAsArray["revenue"], $customer->getRevenue());
	}

	public static function customerList(): iterable
	{
		// this test data are extracted from data\customers.json
		return [
			[
				[
					"id" => 1,
					"name" => "Coca Cola",
					"since" => "2014-06-28",
					"revenue" => 492.12
				],
			],
			[
				[
					"id" => 2,
					"name" => "Teamleader",
					"since" => "2015-01-15",
					"revenue" => 1505.95
				],
			],
			[
				[
					"id" => 3,
					"name" => "Jeroen De Wit",
					"since" => "2016-02-11",
					"revenue" => 0.00
				],
			],
		];
	}
}
