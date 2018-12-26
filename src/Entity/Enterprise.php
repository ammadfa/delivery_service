<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnterpriseRepository")
 */
class Enterprise
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $abn;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="enterprises")
     */
    private $directors;

    public function __construct()
    {
        $this->directors = new ArrayCollection();
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAbn(): string
    {
        return $this->abn;
    }

    public function setAbn(string $abn): self
    {
        $this->abn = $abn;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Person $director): self
    {
        if (!$this->directors->contains($director)) {
            $this->directors[] = $director;
        }

        return $this;
    }

    public function removeDirector(Person $director): self
    {
        if ($this->directors->contains($director)) {
            $this->directors->removeElement($director);
        }

        return $this;
    }
}
