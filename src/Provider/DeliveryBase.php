<?php

namespace App\Provider;

use App\Services\Marketing;

abstract class DeliveryBase {

    protected $deliveryOrder = null;

    public function preProcess() {
        // Something to do before processing the delivery (regardless of the delivery type)
    }

    public function postProcess() {
        if ($this->deliveryOrder->isEmailCampaign()) {
            $campaign = $this->deliveryOrder->getCampaign();
            $campaign->addOrder($this->deliveryOrder);

            $marketing_service = new Marketing();
            $marketing_service->sendSuccess($campaign);
        }
    }
}