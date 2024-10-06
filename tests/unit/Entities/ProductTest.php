<?php

namespace Tests\Entities;

use App\Entities\Product;
use PHPUnit\Framework\TestCase;

/**
 * @group entities
 * @group unit-test
 */
class ProductTest extends TestCase
{
	/**
	 * @dataProvider productsList
	 * @param array $productAsArray
	 */
	public function testGetters(array $productAsArray)
	{
		$product = new Product(
			$productAsArray["id"],
			$productAsArray["description"],
			$productAsArray["category"],
			$productAsArray["price"],
		);

		$this->assertEquals($productAsArray["id"], $product->getId());
		$this->assertEquals($productAsArray["description"], $product->getDescription());
		$this->assertEquals($productAsArray["category"], $product->getCategory());
		$this->assertEquals($productAsArray["price"], $product->getPrice());
	}

	public static function productsList(): iterable
	{
		// this test data are extracted from all 3 orders samples found in folder 'example-orders'
		return [
			[
				[
					"id" => "A101",
					"description" => "Screwdriver",
					"category" => 1,
					"price" => 9.75,
				],
			],
			[
				[
					"id" => "A102",
					"description" => "Electric screwdriver",
					"category" => 1,
					"price" => 49.50,
				],
			],
			[
				[
					"id" => "B101",
					"description" => "Basic on-off switch",
					"category" => 2,
					"price" => 4.99,
				],
			],
			[
				[
					"id" => "B102",
					"description" => "Press button",
					"category" => 2,
					"price" => 4.99,
				],
			],
			[
				[
					"id" => "B103",
					"description" => "Switch with motion detector",
					"category" => 2,
					"price" => 12.95,
				],
			],
		];
	}
}
