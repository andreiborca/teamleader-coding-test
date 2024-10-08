<?php

namespace App\Models\Traits;

trait DiscountsTrait
{
	private array $discounts = [];

	public function addDiscount($discount, string $discountType) : self {
		$this->discounts[$discountType] = $discount;
		$this->calculateTotal();
		return $this;
	}

	public function getDiscounts() : array {
		return $this->discounts;
	}
}