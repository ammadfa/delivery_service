<?php

namespace App\Services;

use App\Entity\Enterprise;

class EnterpriseValidation {

    // Temporary container that represents the enterprise repository
    public $valid_registered_enterprise_names = array(
        'Bayview Motel'
    );

    public function validate(Enterprise $enterprise) {
        if (in_array($enterprise->getName(), $this->valid_registered_enterprise_names)) {
            return true;
        } else {
            return false;
        }
    }
}