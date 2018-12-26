<?php

namespace App\Provider;

use App\Entity\DeliveryOrder;
use App\Services\OrderFulfillment;

/**
 * Class PersonalDelivery
 * @package App\Entity\Provider
 */
class PersonalDelivery extends DeliveryBase implements iDelivery {

    public function process(DeliveryOrder $deliveryOrder) {
        $this->deliveryOrder = $deliveryOrder;

        $this->preProcess();

        $order_fulfillment = new OrderFulfillment();
        $this->deliveryOrder = $order_fulfillment->process($this->deliveryOrder);

        $this->postProcess();

        return $this->deliveryOrder;
    }
}