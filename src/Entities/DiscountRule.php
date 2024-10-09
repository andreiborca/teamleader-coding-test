<?php

namespace App\Entities;

class DiscountRule
{
	private int $id;
	private string $type;
	private array $discountRules;

	public function __construct(
		int $id,
		string $type,
		array $discountRules,
	) {
		$this->id = $id;
		$this->type = $type;
		$this->discountRules = $discountRules;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getDiscountRules(): array
	{
		return $this->discountRules;
	}
}