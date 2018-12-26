<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryOrderRepository")
 */
class DeliveryOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $deliveryType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $source;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $weight;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="deliveryOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $customer;

    protected $fulfillment_status = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campaign", inversedBy="orders")
     */
    private $campaign;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(string $deliveryType): self
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCustomer(): Person
    {
        return $this->customer;
    }

    public function setCustomer(Person $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getFulfillmentStatus(): string
    {
        return $this->fulfillment_status;
    }

    public function setFulfillmentStatus(string $fulfillment_status): self
    {
        $this->fulfillment_status = $fulfillment_status;

        return $this;
    }

    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function isEnterpriseDelivery() {
        return false;
    }

    public function isEmailCampaign() {
        return $this->source === 'Email' && !is_null($this->campaign);
    }
}
