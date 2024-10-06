<?php

namespace App\Transformers;

use App\Exceptions\InvalidOrderFormatException;
use App\Interfaces\OrderItemTransformerInterface;
use App\Interfaces\OrderTransformerInterface;
use App\Models\Order;

class OrderJsonTransformer implements OrderTransformerInterface
{
	private OrderItemTransformerInterface$orderItemTransformer;

	public function __construct(OrderItemTransformerInterface $orderItemTransformer)
	{
		$this->orderItemTransformer = $orderItemTransformer;
	}

	/**
	 * @param string $orderAsJson
	 *
	 * @return Order
	 *
	 * @throws InvalidOrderFormatException
	 */
	public function requestToModel($orderAsJson) : Order {
		$orderAsArray = json_decode($orderAsJson, true);
		$jsonError = json_last_error();

		if ($jsonError != JSON_ERROR_NONE) {
			throw new InvalidOrderFormatException(
				"Order information is not a valid JSON."
			);
		}

		$items = [];
		foreach ($orderAsArray["items"] as $item) {
			$items[] = $this->orderItemTransformer($item);
		}

		return new Order(
			$orderAsArray["id"],
			$orderAsArray["customer-id"],
			$items,
			$orderAsArray["total"],
		);
	}

	public function modelToResponse(Order $order) {
		$items = [];
		foreach ($order->getItems() as $item) {
			$items[] = $this->orderItemTransformer->modelToResponse($item);
		}

		$asArray = [
			"id" =>  $order->getId(),
			"customer-id" => $order->getCustomerId(),
			"items" => $items,
			"total" => $order->getTotal(),
		];

		return $asArray;
	}
}