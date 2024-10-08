<?php

namespace App\DiscountRules;

use App\Interfaces\DiscountRuleInterface;
use App\Models\Order;
use App\Models\OrderItem;

class CheapestProductOfCategory implements DiscountRuleInterface
{
	private string $categoryId;
	private string $percentage;
	private array $productsPrice;

	public function __construct(
		int $categoryId,
		string $percentage,
		array $productsPrice,
	) {
		$this->categoryId = $categoryId;
		$this->percentage = $percentage;
		$this->productsPrice = $productsPrice;
	}

	public function applyDiscount(Order &$order) : Order {
		$orderItemsOfCategory = $this->getOrderItemsOfCategory($order->getItems());
		if ($this->isCriterionMeet($orderItemsOfCategory)) {
			$cheapestProduct = $this->identifyCheapestProduct($orderItemsOfCategory);
			foreach ($order->getItems() as &$orderItem) {
				if ($cheapestProduct["id"] == $orderItem->getProductId()){
					$orderItem->addDiscount($this->percentage, "percentage");
					break;
				}
			}
		}

		return $order;
	}

	/**
	 * @param OrderItem[] $orderItems
	 * @return array
	 */
	private function getOrderItemsOfCategory(array $orderItems) : array {
		$orderItemsOfCategory = [];
		foreach ($orderItems as $orderItem) {
			$temp = [
				"id" => $orderItem->getProductId(),
				"price" => $orderItem->getUnitPrice(),
			];

			if (in_array($temp , $this->productsPrice)) {
				$orderItemsOfCategory[] = $temp;
			}
		}

		return $orderItemsOfCategory;
	}

	private function isCriterionMeet($orderItemsOfCategory) : bool {
		return count($orderItemsOfCategory) >= 2;
	}

	private function identifyCheapestProduct(array $orderItems) : array {
		usort($orderItems, function($product1, $product2) {
			if ($product1["price"] > $product2["price"]) {
				return 1;
			} elseif ($product1["price"] < $product2["price"]) {
				return -1;
			}
			return  0;
		});

		return $orderItems[0];
	}
}