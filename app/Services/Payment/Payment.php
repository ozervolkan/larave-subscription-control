<?php

namespace App\Services\Payment;

class Payment
{
    /**
     * @param float $price
     * @return array
     */
    public function pay(float $price): array
    {
        $payment = [
            'success'=> true,
            'message'=> 'Ödeme başarılı!'
        ];
        return $payment;
    }
}
