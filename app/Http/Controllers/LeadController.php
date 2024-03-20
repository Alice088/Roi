<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;

class LeadController extends Controller
{
    public function update(Request $request)
    {
        $webHookData = $request->input('leads');

        /** @var \AmoCRM\Client\AmoCRMApiClient $apiClient */
        $apiClient = app(AmoCRMApiClient::class);

        $leads   = $apiClient->leads();
        $gotLead = $leads->getOne($webHookData["update"][0]["id"]);

        if (!is_null($gotLead->getCustomFieldsValues())) {
            $profitField = (new NumericCustomFieldValueModel())->setValue(
                LeadModel::calculateProfit(
                    $gotLead->getPrice(),
                    LeadModel::getFieldValueByName($gotLead->getCustomFieldsValues(), "Себестоимость")
                )
            );

            $profitCollection = (new NumericCustomFieldValueCollection())->add($profitField);
            $profitModel      = (new NumericCustomFieldValuesModel())
                ->setValues($profitCollection)
                ->setFieldName("Прибыль")
                ->setFieldId(env("AMOCRM_INTEGRATION_TUGRIK_PROFIT_FIELD_ID"));

            $gotLead->getCustomFieldsValues()->add($profitModel);

            $leads->updateOne($gotLead);
        }
    }

    public function add(Request $request)
    {
        $webHookData = $request->input('leads');

        /** @var \AmoCRM\Client\AmoCRMApiClient $apiClient */
        $apiClient = app(AmoCRMApiClient::class);

        $leads   = $apiClient->leads();
        $gotLead = $leads->getOne($webHookData["add"][0]["id"]);

        if (!is_null($gotLead->getCustomFieldsValues())) {
            $profitField = (new NumericCustomFieldValueModel())->setValue(
                LeadModel::calculateProfit(
                    $gotLead->getPrice(),
                    LeadModel::getFieldValueByName($gotLead->getCustomFieldsValues(), "Себестоимость")
                )
            );

            $profitCollection = (new NumericCustomFieldValueCollection())->add($profitField);
            $profitModel      = (new NumericCustomFieldValuesModel())
                ->setValues($profitCollection)
                ->setFieldName("Прибыль")
                ->setFieldId(env("AMOCRM_INTEGRATION_TUGRIK_PROFIT_FIELD_ID"));

            $gotLead->getCustomFieldsValues()->add($profitModel);

            $leads->updateOne($gotLead);
        }
    }
}
