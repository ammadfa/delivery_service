<?php

namespace App\Provider;

use \App\Entity\DeliveryOrder;

interface iDelivery {

    public function process(DeliveryOrder $deliveryOrder);
}