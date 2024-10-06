<?php

namespace App\Interfaces;

use App\Models\Order;

interface OrderTransformerInterface
{
	public function requestToModel($order) : Order;
	public function modelToResponse(Order $order);
}