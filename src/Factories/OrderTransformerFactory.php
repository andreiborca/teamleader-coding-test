<?php

namespace App\Factories;

use App\Exceptions\InvalidOrderTransformerFactoryFormatException;
use App\Interfaces\OrderTransformerInterface;
use App\Transformers\OrderJsonTransformer;

class OrderTransformerFactory
{
	public static function create(string $format): OrderTransformerInterface {
		$orderTransformer = null;
		switch ($format) {
			case "application/json":
				$orderTransformer = new OrderJsonTransformer();
				break;
			default:
				throw new InvalidOrderTransformerFactoryFormatException(
					$format,
					[
						"application/json",
					],
				);
				break;
		}
		
		return $orderTransformer;
	}
}