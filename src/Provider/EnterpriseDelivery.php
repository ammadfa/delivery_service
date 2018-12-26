<?php

namespace App\Provider;

use App\Entity\DeliveryOrder;
use App\Services\EnterpriseValidation;
use App\Services\OrderFulfillment;

/**
 * Class EnterpriseDelivery
 * @package App\Entity\Provider
 */
class EnterpriseDelivery extends DeliveryBase implements iDelivery {

    public function process(DeliveryOrder $deliveryOrder) {
        $this->deliveryOrder = $deliveryOrder;

        $this->preProcess();

        $order_fulfillment = new OrderFulfillment();
        $this->deliveryOrder = $order_fulfillment->process($this->deliveryOrder);

        $this->postProcess();

        return $this->deliveryOrder;
    }

    public function preProcess() {
        parent::preProcess();

        $enterprise_validation_service = new EnterpriseValidation();
        $enterprise_validation_service->validate($this->deliveryOrder->getEnterprise());
    }
}