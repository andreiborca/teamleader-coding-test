<?php

namespace App\Interfaces;

interface ProductsRepositoryInterface
{
	public function getAllProductsIdFromCategory(int $categoryId) : array;
	public function getProductsPriceForCategory(int $categoryId) : array;
}