<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Models\Transaction;
use Illuminate\Database\QueryException;

class TransactionRepository
{
    /**
     * @param int $subscription_id
     * @return mixed
     */
    public function create(int $subscription_id): mixed
    {
        $data = [
            'subscription_id'=> $subscription_id,
            'price'=> Constants::SUBSCRIPTION_PRICE,
        ];

        return Transaction::create($data);
    }

}
