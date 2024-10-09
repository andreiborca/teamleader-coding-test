<?php

namespace App\Repositories;

use App\Entities\DiscountRule;

class DiscountRulesRepository
{
	/**
	 * @var DiscountRule[]
	 */
	private array $discountRules = [];

	public function __construct()
	{
		$discountRules = json_decode(
			file_get_contents(realpath(".") . "/../data/discount-rules.json"),
			true
		);

		foreach ($discountRules as $discountRule) {
			$this->discountRules[] = new DiscountRule(
				$discountRule["id"],
				$discountRule["type"],
				$discountRule["discount-rules"],
			);
		}
	}

	public function getAll() : array {
		return $this->discountRules;
	}
}