<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Enterprise", mappedBy="directors")
     */
    private $enterprises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeliveryOrder", mappedBy="customer", orphanRemoval=true)
     */
    private $deliveryOrders;

    public function __construct()
    {
        $this->enterprises = new ArrayCollection();
        $this->deliveryOrders = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|Enterprise[]
     */
    public function getEnterprises(): Collection
    {
        return $this->enterprises;
    }

    public function addEnterprise(Enterprise $enterprise): self
    {
        if (!$this->enterprises->contains($enterprise)) {
            $this->enterprises[] = $enterprise;
            $enterprise->addDirector($this);
        }

        return $this;
    }

    public function removeEnterprise(Enterprise $enterprise): self
    {
        if ($this->enterprises->contains($enterprise)) {
            $this->enterprises->removeElement($enterprise);
            $enterprise->removeDirector($this);
        }

        return $this;
    }

    /**
     * @return Collection|DeliveryOrder[]
     */
    public function getDeliveryOrders(): Collection
    {
        return $this->deliveryOrders;
    }

    public function addDeliveryOrder(DeliveryOrder $deliveryOrder): self
    {
        if (!$this->deliveryOrders->contains($deliveryOrder)) {
            $this->deliveryOrders[] = $deliveryOrder;
            $deliveryOrder->setCustomer($this);
        }

        return $this;
    }

    public function removeDeliveryOrder(DeliveryOrder $deliveryOrder): self
    {
        if ($this->deliveryOrders->contains($deliveryOrder)) {
            $this->deliveryOrders->removeElement($deliveryOrder);
            // set the owning side to null (unless already changed)
            if ($deliveryOrder->getCustomer() === $this) {
                $deliveryOrder->setCustomer(null);
            }
        }

        return $this;
    }
}
