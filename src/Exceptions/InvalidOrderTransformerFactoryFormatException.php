<?php

namespace App\Exceptions;

use Throwable;

class InvalidOrderTransformerFactoryFormatException extends \Exception
{
	private array $supportedFormats;

	public function __construct(string $format, array $supportedFormats, $code = 0, Throwable $previous = null)
	{
		$this->supportedFormats = $supportedFormats;

		$message = sprintf(
			"Invalid format '%s'. Accepted values are: %s",
			$format,
			implode(",", $supportedFormats)
		);

		parent::__construct($message, $code, $previous);
	}

	public function getSupportedFormats() : array {
		return $this->supportedFormats;
	}
}