<?php

namespace App\Factories;;

use App\DiscountRules\CheapestProductOfCategory;
use App\DiscountRules\CustomerRevenueOver;
use App\DiscountRules\FreeItemOnQuantity;
use App\Entities\DiscountRule;
use App\Interfaces\ProductsRepositoryInterface;
use App\Repositories\DiscountRulesRepository;

class DiscountRulesFactory
{
	private ProductsRepositoryInterface $productsRepository;
	private DiscountRulesRepository $discountRulesRepository;

	public function __construct(
		DiscountRulesRepository $discountRulesRepository,
		ProductsRepositoryInterface $productsRepository,
	) {
		$this->discountRulesRepository = $discountRulesRepository;
		$this->productsRepository = $productsRepository;
	}

	public function create() : array {
		$discountRules = [];
		$discountRulesEntities = $this->discountRulesRepository->getAll();
		foreach ($discountRulesEntities as $discountRule) {
			switch ($discountRule->getType()) {
				case "FreeItemOnQuantity":
					$discountRules["product"][] = $this->initFreeItemOnQuantity($discountRule);
					break;
				case "CheapestProductOfCategory":
					$discountRules["product"][] = $this->initCheapestProductOfCategory($discountRule);
					break;
				case "CustomerRevenueOver":
					$discountRules["order"][] = $this->initCustomerRevenueOver($discountRule);
					break;
			}
		}
		return $discountRules;
	}

	public function initFreeItemOnQuantity(DiscountRule $discountRule): FreeItemOnQuantity
	{
		$rules = $discountRule->getDiscountRules();
		return new FreeItemOnQuantity(
			$rules["product-category-id"],
			$rules["quantity-criterion"],
			$rules["free-quantity"],
			$this->productsRepository->getAllProductsIdFromCategory($rules["product-category-id"]),
		);

	}

	public function initCheapestProductOfCategory(DiscountRule $discountRule) : CheapestProductOfCategory {
		$rules = $discountRule->getDiscountRules();
		return new CheapestProductOfCategory(
			$rules["categoryId"],
      		$rules["minimum_distinct_products"],
      		$rules["percentage"],
			$this->productsRepository->getProductsPriceForCategory($rules["categoryId"]),
		);
	}

	public function initCustomerRevenueOver(DiscountRule $discountRule) : CustomerRevenueOver {
		$rules = $discountRule->getDiscountRules();
		return new CustomerRevenueOver(
			$rules["revenue-over"],
			$rules["percentage"],
		);
	}
}