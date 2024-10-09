<?php

namespace App\Repositories;

use App\Entities\Customer;
use App\Interfaces\CustomerRepositoryInterface;
use DateTime;

class CustomerRepository implements CustomerRepositoryInterface
{
	private array $customers;

	public function __construct()
	{
		$this->customers = json_decode(file_get_contents(realpath(".") . "/../data/customers.json"), true);
	}

	public function findCustomerById(int $customerId) : Customer|null {
		$customer = null;

		$key = array_search($customerId, array_column($this->customers, "id"));
		if (is_numeric($key)) {
			$customer = new Customer(
				$this->customers[$key]["id"],
				$this->customers[$key]["name"],
				new DateTime($this->customers[$key]["since"]),
				$this->customers[$key]["revenue"],
			);
		}
		return $customer;

	}
}