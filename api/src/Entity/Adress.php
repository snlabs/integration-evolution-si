<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?string $street = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?int $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?string $ville = null;

    #[ORM\OneToOne(mappedBy: 'adress', cascade: ['persist', 'remove'])]
    private ?RegionalAdvisory $regionalAdvisory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getRegionalAdvisory(): ?RegionalAdvisory
    {
        return $this->regionalAdvisory;
    }

    public function setRegionalAdvisory(?RegionalAdvisory $regionalAdvisory): static
    {
        // unset the owning side of the relation if necessary
        if ($regionalAdvisory === null && $this->regionalAdvisory !== null) {
            $this->regionalAdvisory->setAdress(null);
        }

        // set the owning side of the relation if necessary
        if ($regionalAdvisory !== null && $regionalAdvisory->getAdress() !== $this) {
            $regionalAdvisory->setAdress($this);
        }

        $this->regionalAdvisory = $regionalAdvisory;

        return $this;
    }
}
