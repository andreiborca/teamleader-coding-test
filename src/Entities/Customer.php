<?php

namespace App\Entities;

use DateTime;

class Customer
{
	private int $id;
	private string $name;
	private DateTime $since;
	private float $revenue;

	public function __construct(
		int $id,
		string $name,
		DateTime $since,
		float $revenue,
	) {
		$this->id = $id;
		$this->name = $name;
		$this->since = $since;
		$this->revenue = $revenue;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getSince(): DateTime
	{
		return $this->since;
	}

	public function getRevenue(): float
	{
		return $this->revenue;
	}
}