<?php

namespace App\Entities;

class Product
{
	private int $id;
	private string $description;
	private int $category;
	private float $price;

	private function __construct(
		int $id,
		string $description,
		int $category,
		float $price,
	) {
		$this->id = $id;
		$this->description = $description;
		$this->category = $category;
		$this->price = $price;
	}
	public function getId(): int
	{
		return $this->id;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getCategory(): int
	{
		return $this->category;
	}

	public function getPrice(): float
	{
		return $this->price;
	}
}