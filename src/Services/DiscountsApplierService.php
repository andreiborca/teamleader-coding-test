<?php

namespace App\Services;

use App\Entities\Customer;
use App\Interfaces\DiscountRuleInterface;
use App\Models\Order;

class DiscountsApplierService
{
	/**
	 * @var DiscountRuleInterface[]
	 */
	private array $discountRules;

	/**
	 * DiscountsApplierService constructor.
	 *
	 * @param DiscountRuleInterface[] $discountRules
	 */
	public function __construct(
		array $discountRules,
	) {
		$this->discountRules = $discountRules;
	}

	public function apply(Order $order, Customer $user) : Order {
		foreach ($this->discountRules["product"] as $discountRule) {
			$discountRule->applyDiscount($order);
		}

		foreach ($this->discountRules["order"] as $discountRule) {
			$discountRule->applyCustomerDiscount($order, $user);
		}
		return $order;
	}
}