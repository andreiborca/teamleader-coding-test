<?php

namespace App\DiscountRules;

use App\Interfaces\DiscountRuleInterface;
use App\Models\Order;

class FreeItemOnQuantity implements DiscountRuleInterface
{
	private string $categoryId;
	/**
	 * @var int Represents on how many items the free ones are granted
	 */
	private int $quantityCriterion;
	private int $freeQuantity;
	private array $productsId = [];

	/**
	 *
	 * @param string $categoryId
	 * @param int $quantityCriterion Represents on how many items the free ones are granted
	 * @param int $freeQuantity
	 * @param array $productsId Must contain the id of the products of category for which the discount is applicable
	 */
	public function __construct(
		string $categoryId,
		int $quantityCriterion,
		int $freeQuantity,
		array $productsId,
	) {
		$this->categoryId = $categoryId;
		$this->quantityCriterion = $quantityCriterion;
		$this->freeQuantity = $freeQuantity;
		$this->productsId = $productsId;
	}

	private function isCriteriaMeet(string $productId, int $quantity) : bool {
		return in_array($productId, $this->productsId)
			&& $quantity >= $this->quantityCriterion;
	}

	public function applyDiscount(Order &$order)
	{
		foreach ($order->getItems() as &$orderItem) {
			if ($this->isCriteriaMeet($orderItem->getProductId(), $orderItem->getQuantity())) {
				$freeQuantity = $orderItem->getQuantity() / $this->quantityCriterion;
				$orderItem->setFreeQuantity($freeQuantity);
			}
		}
	}

	private function calculateQuantities(int $orderedQuantity) : array {
		$quantities = [
			"paidQuantity" => 0,
			"freeQuantity" => 0,
		];

		while ($orderedQuantity >= 5 ) {
			$quantities["paidQuantity"] += 5;
			$quantities["freeQuantity"] += 1;

			$orderedQuantity -= 6;
		}

		if ($orderedQuantity > 0) {
			$quantities["paidQuantity"] += $orderedQuantity;
		}

		return $quantities;
	}
}