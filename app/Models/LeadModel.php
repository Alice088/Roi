<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AmoCRM\Collections\CustomFieldsValuesCollection;

class LeadModel extends Model
{
    use HasFactory;

    public static function calculateProfit(string|int|null $price, string|int|null $cost_price): int
    {
        if (!is_null($price) && !is_null($cost_price)) {
            return +$price - +$cost_price;
        } else {
            return 0;
        }
    }

    public static function getFieldValueByName(
        CustomFieldsValuesCollection|null $customFieldsValuesCollection,
        string $fieldName
    ): ?int {
        if (is_null($customFieldsValuesCollection)) {
            return null;
        } {
            foreach ($customFieldsValuesCollection as $customFieldValue) {
                if ($customFieldValue->getFieldName() === $fieldName) {
                    foreach ($customFieldValue->getValues() as $value) {
                        return $value->getValue();
                    }
                }
            }
        }
        return null;
    }
}
