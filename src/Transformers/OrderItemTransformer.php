<?php

namespace App\Transformers;

use App\Interfaces\OrderItemTransformerInterface;
use App\Models\OrderItem;

class OrderItemTransformer implements OrderItemTransformerInterface
{
	public function requestToModel(array $orderItem) : OrderItem {
		return new OrderItem(
			$orderItem["product-id"],
			$orderItem["quantity"],
			$orderItem["unit-price"],
			$orderItem["total"],
		);
	}

	public function modelToResponse(OrderItem $orderItem) : array {
		$asArray = [
			"product-id" => $orderItem->getProductId(),
			"quantity" => (string)$orderItem->getQuantity(),
			"unit-price" => number_format($orderItem->getUnitPrice(), 2, ".", ""),
			"total" => number_format($orderItem->getTotal(), 2, ".", ""),
		];

		$discounts = $orderItem->getDiscounts();

		if ($orderItem->getFreeQuantity() > 0) {
			$discounts["free-quantity"] = (string)$orderItem->getFreeQuantity();
		}

		if (!empty($discounts)) {
			$asArray["discounts"] = $discounts;
		}

		return $asArray;
	}
}