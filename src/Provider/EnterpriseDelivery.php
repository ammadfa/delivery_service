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

    public function process() {
        $this->preProcess();

        parent::process();

        $this->postProcess();

        return $this->deliveryOrder;
    }

    public function preProcess() {
        parent::preProcess();

        $enterprise_validation_service = new EnterpriseValidation();
        $enterprise_validation_service->validate($this->deliveryOrder->getEnterprise());
    }
}