<?php

namespace App\Repositories;

use App\Interfaces\ProductsRepositoryInterface;

class ProductsRepository implements ProductsRepositoryInterface
{
	private array $products;

	public function __construct()
	{
		$this->products = json_decode(file_get_contents(realpath(".") . "/../data/products.json"), true);
	}

	public function getAllProductsIdFromCategory(int $categoryId) : array{
		$productsId = [];

		foreach ($this->products as $product) {
			if ($categoryId == $product["category"]) {
				$productsId[] = $product["id"];
			}
		}

		return $productsId;
	}
}