<?php

namespace App\DiscountRules;

use App\Entities\Customer;
use App\Interfaces\DiscountRuleInterface;
use App\Models\Order;
use \Exception;

final class CustomerRevenueOver implements DiscountRuleInterface
{
	private string $percentage;
	private float $revenueOver;
	private Customer|null $customer = null;

	public function __construct(
		float $revenueOver,
		string $percentage,
	) {
		$this->revenueOver = $revenueOver;
		$this->percentage = $percentage;
	}

	private function isCriterionMeet($amount) : bool {
		return $amount > $this->revenueOver;
	}

	/**
	 * @param Order $order
	 *
	 * @throws Exception
	 */
	public function applyDiscount(Order &$order) {
		if (!isset($this->customer)) {
			throw new Exception("Missing customer data. Use method CustomerRevenueOver::applyCustomerDiscount");
		}

		if ($this->isCriterionMeet($this->customer->getRevenue())) {
			$order->addDiscount($this->percentage, "percentage");
		}
	}

	public function applyCustomerDiscount(Order &$order, Customer $customer) {
		$this->customer = $customer;
		$this->applyDiscount($order);
		$this->customer = null;
	}
}