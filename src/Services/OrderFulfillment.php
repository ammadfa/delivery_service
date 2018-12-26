<?php

namespace App\Services;

use App\Entity\DeliveryOrder;

class OrderFulfillment {

    public function process(DeliveryOrder $deliveryOrder) {
        $deliveryOrder->setFulfillmentStatus('COMPLETED');
        return $deliveryOrder;
    }
}