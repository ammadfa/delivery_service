<?php

namespace App\Provider;

use App\Services\Marketing;
use App\Services\OrderFulfillment;

abstract class DeliveryBase {

    protected $deliveryOrder = null;

    public function preProcess() {
        // Something to do before processing the delivery (regardless of the delivery type)
    }

    public function process() {
        $order_fulfillment = new OrderFulfillment();
        $this->deliveryOrder = $order_fulfillment->process($this->deliveryOrder);
    }

    public function postProcess() {
        if ($this->deliveryOrder->isEmailCampaign()) {
            $campaign = $this->deliveryOrder->getCampaign();
            $campaign->addOrder($this->deliveryOrder);

            $marketing_service = new Marketing();
            $marketing_service->sendSuccess($campaign);
        }
    }

    public function setDeliveryOrder($deliveryOrder) {
        $this->deliveryOrder = $deliveryOrder;
        return $this;
    }
}