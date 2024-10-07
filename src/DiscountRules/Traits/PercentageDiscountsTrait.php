<?php

namespace App\DiscountRules\Traits;

trait PercentageDiscountsTrait
{
	private function applyPercentageDiscount(float $value, string $percentage) : float {
		// Remove the '%' character if it's present in the percentage string
		$percentage = str_replace('%', '', $percentage);

		// Convert percentage to a decimal (e.g., '20%' becomes 0.20)
		$percentageDecimal = (float) $percentage / 100;

		// Subtract the percentage from the value
		return $value - ($value * $percentageDecimal);
	}
}