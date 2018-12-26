<?php

namespace App\Entity;

use App\Repository\DeliveryOrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryOrderRepository")
 */
class EnterpriseDeliveryOrder extends DeliveryOrder
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $onBehalf;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    protected $enterprise;

    public function getOnBehalf(): string
    {
        return $this->onBehalf;
    }

    public function setOnBehalf(string $onBehalf): self
    {
        $this->onBehalf = $onBehalf;

        return $this;
    }

    public function getEnterprise()
    {
        return $this->enterprise;
    }

    public function setEnterprise($enterprise): self
    {
        $this->enterprise = $enterprise;

        return $this;
    }

    public function isEnterpriseDelivery() {
        return true;
    }
}
