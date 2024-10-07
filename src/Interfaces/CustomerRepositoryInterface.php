<?php

namespace App\Interfaces;

use App\Entities\Customer;

interface CustomerRepositoryInterface
{
	public function findCustomerById(int $customerId) : Customer|null;
}