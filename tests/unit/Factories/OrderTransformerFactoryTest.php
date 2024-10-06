<?php

namespace Tests\Factories;

use App\Exceptions\InvalidOrderTransformerFactoryFormatException;
use App\Factories\OrderTransformerFactory;
use App\Transformers\OrderJsonTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @group factories
 * @group unit-test
 */
class OrderTransformerFactoryTest extends TestCase
{
	public function testOrderJsonTransformerInitialization() {
		$transformer = OrderTransformerFactory::create("application/json");
		$this->assertTrue($transformer instanceof OrderJsonTransformer);
	}

	public function test() {
		$this->expectException(InvalidOrderTransformerFactoryFormatException::class);
		$transformer = OrderTransformerFactory::create("app/json");
	}
}
