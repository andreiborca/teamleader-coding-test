<?php

namespace App\Models;

use Exception;

class Order implements SplObserver
{
	private int $id;
	private int $customerId;
	/** @var OrderItem[] */
	private array $items;
	private float $total;

	/**
	 * Order constructor.
	 *
	 * @param int $id
	 * @param int $customerId
	 * @param array $items
	 * @param float $total
	 *
	 * @throws Exception
	 */
	public function __construct(
		int $id,
		int $customerId,
		array $items,
		float $total
	) {
		$this->processItems($items);
		$this->id = $id;
		$this->customerId = $customerId;
		$this->total = $total;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getCustomerId(): int
	{
		return $this->customerId;
	}

	public function getItems(): array
	{
		return $this->items;
	}

	public function getTotal(): float
	{
		return $this->total;
	}

	/**
	 * @param array $items
	 *
	 * @throws Exception
	 */
	private function processItems(array $items) {
		foreach ($items as $item) {
			$this->validateItem($item);
			$this->items[] = $item;
		}
	}

	/**
	 * @param $item
	 *
	 * @throws Exception
	 */
	private function validateItem($item) {
		if (!$item instanceof OrderItem) {
			throw new Exception("All elements must be of type OrderItem");
		}
	}

	private function calculateTotal() {
		$total = 0;
		foreach ($this->items as $item) {
			$total += $item->getTotal();
		}
		$this->total = $total;
	}
}