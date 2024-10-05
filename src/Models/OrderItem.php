<?php

namespace App\Models;

class OrderItem implements SplSubject
{
	private string $productId;
	private int $quantity;
	private float $unitPrice;
	private float $total;

	public function __construct(
		string $productId,
		int $quantity,
		float $unitPrice,
		float $total,
	) {
		$this->productId = $productId;
		$this->quantity = $quantity;
		$this->unitPrice = $unitPrice;
		$this->total = $total;
	}
	public function getProductId(): string
	{
		return $this->productId;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

	public function setQuantity(int $quantity): self
	{
		$this->quantity = $quantity;
		$this->calculateTotal();
		return $this;
	}

	public function getUnitPrice(): float
	{
		return $this->unitPrice;
	}

	public function getTotal(): float
	{
		return $this->total;
	}

	private function calculateTotal() {
		$this->total = $this->quantity * $this->unitPrice;
		$this->notify();
	}
}