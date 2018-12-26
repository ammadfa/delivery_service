<?php

namespace App\Provider;

use App\Entity\DeliveryOrder;
use App\Services\OrderFulfillment;

/**
 * Class PersonalDelivery
 * @package App\Entity\Provider
 */
class PersonalDelivery extends DeliveryBase implements iDelivery {

    public function process() {
        $this->preProcess();

        parent::process();

        $this->postProcess();

        return $this->deliveryOrder;
    }
}