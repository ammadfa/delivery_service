<?php

namespace App\Services;

use App\Entity\Campaign;

class Marketing {

    public function sendSuccess(Campaign $campaign) {
        $message = 'Congrats Marketing Team on a job well done on ' . $campaign->getName() . '. ';
        $message .= 'So far we have processed about ' . count($campaign->getOrders()) . ' orders originating from your campaign efforts.';

        $comm_result = array(
            'status' => 'success',
            'code' => 200,
            'message' => $message,
        );

        return $comm_result;
    }
}