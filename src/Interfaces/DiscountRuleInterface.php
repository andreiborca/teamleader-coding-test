<?php

namespace App\Interfaces;

use App\Models\Order;

interface DiscountRuleInterface
{
	public function applyDiscount(Order &$order);
}