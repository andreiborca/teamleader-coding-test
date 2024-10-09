<?php

namespace Tests\Factories;

use App\DiscountRules\CheapestProductOfCategory;
use App\DiscountRules\CustomerRevenueOver;
use App\DiscountRules\FreeItemOnQuantity;
use App\Entities\DiscountRule;
use App\Factories\DiscountRulesFactory;
use App\Repositories\DiscountRulesRepository;
use App\Repositories\ProductsRepository;
use PHPUnit\Framework\TestCase;

/**
 * @group factories
 * @group unit-test
 */
class DiscountRulesFactoryTest extends TestCase
{
	/**
	 * @var DiscountRulesFactory
	 */
	private DiscountRulesFactory $discountRuleFactory;

	private $productsRepositoryMock;

	public function setUp(): void
	{
		$discountRulesRepositoryMock = $this->createMock(DiscountRulesRepository::class);
		$discountRulesRepositoryMock->method("getAll")
			->willReturn(
				[
					new DiscountRule(
						1,
						"CustomerRevenueOver",
						[
							"revenue-over" => "1000",
							"percentage" => "10%",
						]
					),
					new DiscountRule(
						2,
						"FreeItemOnQuantity",
						[
							"product-category-id" => "2",
							"quantity-criterion" => "5",
							"free-quantity" => "1",
						]
					),
					new DiscountRule(
						3,
						"CheapestProductOfCategory",
						[
							"categoryId" => "1",
							"minimum_distinct_products" => "2",
							"percentage" => "20%",
						]
					)
				]
			);

		$productsRepositoryMock = $this->createMock(ProductsRepository::class);
		$productsRepositoryMock->method("getAllProductsIdFromCategory")
			->withAnyParameters()
			->willReturn(["B101", "B102", "B103"]);
		$productsRepositoryMock->method("getProductsPriceForCategory")
			->withAnyParameters()
			->willReturn([
				[
					"id" => "A101",
					"price" => "9.75"
				],
				[
					"id" => "A102",
					"price" => "49.50",
				],
			]);

		$this->productsRepositoryMock = $productsRepositoryMock;
	 	$this->discountRuleFactory = new DiscountRulesFactory(
			$discountRulesRepositoryMock,
			$productsRepositoryMock,
		);
	}

	public function testInitCustomerRevenueOver() {
		$discountRule = new CustomerRevenueOver(1000, "10%");

		$this->assertEquals(
			$discountRule,
			$this->discountRuleFactory->initCustomerRevenueOver(
				new DiscountRule(
					1,
					"CustomerRevenueOver",
					[
						"revenue-over" => "1000",
      					"percentage" => "10%",
					]
				)
			)
		);
	}

	public function testInitFreeItemOnQuantity() {
		$discountRule = new FreeItemOnQuantity(
			2,
			5,
			1,
			$this->productsRepositoryMock->getAllProductsIdFromCategory(2),
		);

		$this->assertEquals(
			$discountRule,
			$this->discountRuleFactory->initFreeItemOnQuantity(
				new DiscountRule(
					2,
					"FreeItemOnQuantity",
					[
						"product-category-id" => "2",
						"quantity-criterion" => "5",
						"free-quantity" => "1",
					]
				)
			)
		);
	}

	public function testInitCheapestProductOfCategory() {
		$discountRule = new CheapestProductOfCategory(
			1,
			2,
			"20%",
			$this->productsRepositoryMock->getProductsPriceForCategory(1),
		);

		$this->assertEquals(
			$discountRule,
			$this->discountRuleFactory->initCheapestProductOfCategory(
				new DiscountRule(
					3,
					"CheapestProductOfCategory",
					[
						"categoryId" => "1",
      					"minimum_distinct_products" => "2",
      					"percentage" => "20%",
					]
				)
			)
		);
	}

	public function testConstructor() {
		$freeItemsCategoryId = 2;
		$discountRules["product"][] = new FreeItemOnQuantity(
			$freeItemsCategoryId,
			5,
			1,
			$this->productsRepositoryMock->getAllProductsIdFromCategory($freeItemsCategoryId),
		);

		$productCategoryId = 1;
		$discountRules["product"][] = new CheapestProductOfCategory(
			$productCategoryId,
			2,
			"20%",
			$this->productsRepositoryMock->getProductsPriceForCategory($productCategoryId),
		);

		$discountRules["order"][] = new CustomerRevenueOver(1000, "10%");

		$this->assertEquals(
			$discountRules,
			$this->discountRuleFactory->create()
		);
	}
}
