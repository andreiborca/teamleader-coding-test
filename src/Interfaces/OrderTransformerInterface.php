<?php

namespace App\Interfaces;

interface OrderTransformerInterface
{
	public function requestToModel();
	public function modelToResponse();
}