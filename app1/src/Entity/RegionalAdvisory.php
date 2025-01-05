<?php

namespace App\Entity;

use App\Repository\RegionalAdvisoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: RegionalAdvisoryRepository::class)]
class RegionalAdvisory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['regions_list'])]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    #[Groups(['regions_list'])]
    private ?string $civilite = null;

    #[ORM\Column(length: 255)]
    #[Groups(['regions_list'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['regions_list'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['regions_list'])]
    private ?string $groupePolitique = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['regions_list'])]
    private ?string $fonctionExecutif = null;

    #[ORM\Column(length: 255)]
    #[Groups(['regions_list'])]
    private ?string $villeNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['regions_list'])]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\ManyToOne(inversedBy: 'regionalAdvisories', cascade: ['persist'])]
    private ?Region $region = null;
   
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getGroupePolitique(): ?string
    {
        return $this->groupePolitique;
    }

    public function setGroupePolitique(?string $groupePolitique): static
    {
        $this->groupePolitique = $groupePolitique;

        return $this;
    }

    public function getFonctionExecutif(): ?string
    {
        return $this->fonctionExecutif;
    }

    public function setFonctionExecutif(?string $fonctionExecutif): static
    {
        $this->fonctionExecutif = $fonctionExecutif;

        return $this;
    }


    public function getVilleNaissance(): ?string
    {
        return $this->villeNaissance;
    }

    public function setVilleNaissance(string $villeNaissance): static
    {
        $this->villeNaissance = $villeNaissance;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

}
