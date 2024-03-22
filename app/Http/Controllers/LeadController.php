<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use Cache;

class LeadController extends Controller
{
    public function update(Request $request)
    {
        try {
            $webHookData = $request->input('leads');

            /** @var \AmoCRM\Client\AmoCRMApiClient $apiClient */
            $apiClient        = app(AmoCRMApiClient::class);
            $leads            = $apiClient->leads();
            $lead             = new LeadModel($leads->getOne($webHookData["update"][0]["id"]));
            $currentPrice     = $webHookData["update"][0]["price"];
            $currentCostPrice = $webHookData["update"][0]["custom_fields"][0]["values"][0]["value"];

            if ($lead->hasCustomFields() && (Cache::get("oldPrice") !== $currentPrice || Cache::get("oldCostPrice") !== $currentCostPrice)) {
                Cache::set("oldPrice", $currentPrice);
                Cache::set("oldCostPrice", $currentCostPrice);

                $profitCollection = (new NumericCustomFieldValueCollection())->add(
                    (new NumericCustomFieldValueModel())->setValue($lead->calculateProfit())
                );

                $profitModel = (new NumericCustomFieldValuesModel())
                    ->setValues($profitCollection)
                    ->setFieldName("Прибыль")
                    ->setFieldId(env("AMOCRM_INTEGRATION_TUGRIK_PROFIT_FIELD_ID"));

                $lead->getLead()->getCustomFieldsValues()->add($profitModel);

                $leads->updateOne($lead->getLead());
            }

            return response(status: 200);
        } catch (\Exception $error) {
            return response("Something went wrong", 500);
        }
    }

    public function add(Request $request)
    {
        try {
            $webHookData = $request->input('leads');

            /** @var \AmoCRM\Client\AmoCRMApiClient $apiClient */
            $apiClient        = app(AmoCRMApiClient::class);
            $leads            = $apiClient->leads();
            $lead             = new LeadModel($leads->getOne($webHookData["add"][0]["id"]));
            $currentPrice     = $webHookData["add"][0]["price"];
            $currentCostPrice = $webHookData["add"][0]["custom_fields"][0]["values"][0]["value"];

            if ($lead->hasCustomFields() && (Cache::get("oldPrice") !== $currentPrice || Cache::get("oldCostPrice") !== $currentCostPrice)) {
                Cache::set("oldPrice", $currentPrice);
                Cache::set("oldCostPrice", $currentCostPrice);

                $profitCollection = (new NumericCustomFieldValueCollection())->add(
                    (new NumericCustomFieldValueModel())->setValue($lead->calculateProfit())
                );

                $profitModel = (new NumericCustomFieldValuesModel())
                    ->setValues($profitCollection)
                    ->setFieldName("Прибыль")
                    ->setFieldId(env("AMOCRM_INTEGRATION_TUGRIK_PROFIT_FIELD_ID"));

                $lead->getLead()->getCustomFieldsValues()->add($profitModel);

                $leads->updateOne($lead->getLead());
            }

            return response(status: 200);
        } catch (\Exception $error) {
            return response("Something went wrong", 500);
        }
    }
}
