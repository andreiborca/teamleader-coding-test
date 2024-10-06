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
			"quantity" => $orderItem->getQuantity(),
			"unit-price" => $orderItem->getUnitPrice(),
			"total" => $orderItem->getTotal(),
		];

		return $asArray;
	}
}