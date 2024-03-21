<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AmoCRM\Models\LeadModel as BaseLeadModel;
use App\Traits\calculateProfitTrait;

class LeadModel extends Model
{
    use HasFactory;
    use calculateProfitTrait;

    /**
     * Lead model
     * @var BaseLeadModel $lead
     */
    private $lead;

    public function __construct(BaseLeadModel|null $lead)
    {
        $this->lead = $lead;
    }

    public function getLead(): BaseLeadModel
    {
        return $this->lead;
    }

    public function hasCustomFields(): bool
    {
        return !is_null($this->lead->getCustomFieldsValues());
    }

    public function getFieldValueByName(string $fieldName): ?int
    {
        $customFieldsValuesCollection = $this->lead->getCustomFieldsValues();

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
