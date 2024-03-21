<?php

namespace App\Traits;

trait calculateProfitTrait
{
    public function calculateProfit(): int
    {
        $price      = $this->lead->getPrice();
        $cost_price = $this->getFieldValueByName("Себестоимость");

        if (!is_null($price) && !is_null($cost_price)) {
            return +$price - +$cost_price;
        } else {
            return 0;
        }
    }
}
