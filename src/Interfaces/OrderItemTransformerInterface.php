<?php

namespace App\Interfaces;

use App\Models\OrderItem;

interface OrderItemTransformerInterface
{
	public function requestToModel(array $orderItem) : OrderItem;
	public function modelToResponse(OrderItem $orderItem);
}