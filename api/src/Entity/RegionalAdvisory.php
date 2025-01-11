<?php

namespace App\Entity;

use App\Repository\RegionalAdvisoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

#[ORM\Entity(repositoryClass: RegionalAdvisoryRepository::class)]

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "regional_advisories_show",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = { "regional_advisory_list" })
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "deleteRegionalAdvisory",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = { "regional_advisory_list" })
 * )
 * 
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "updateRegionalAdvisory",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = { "regional_advisory_list" })
 * )
 * 
 *  @Hateoas\Relation(
 *      "regional_advisory",
 *      href = @Hateoas\Route(
 *          "region_advisories",
 *          parameters = { "id" = "expr(object.getRegion().getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = { "regional_advisory_show" })
 * )
 * 
 *  * @Hateoas\Relation(
 *     "region",
 *     href = @Hateoas\Route(
 *         "region_show",
 *         parameters = { "id" = "expr(object.getRegion().getId())" }
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups = { "regional_advisory_list" })
 * )
 * 
 */
class RegionalAdvisory
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?string $civilite = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[Serializer\SerializedName("groupe_politique")]
    private ?string $groupePolitique = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[Serializer\SerializedName("liste_electorale")]
    private ?string $listeElectorale = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[Serializer\SerializedName("fonction_executif")]
    private ?string $fonctionExecutif = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[Serializer\SerializedName("ville_naissance")]
    private ?string $villeNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[Serializer\SerializedName("date_naissance")]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\ManyToOne(inversedBy: 'regionalAdvisories', cascade: ['persist'])]
    private ?Region $region = null;
 

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?string $mandature = null;

    #[ORM\Column(nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?bool $executif = null;

    #[ORM\Column(nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[Serializer\SerializedName("commission_permanente")]
    private ?bool $commissionPermanente = null;
    

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    private ?string $mail = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    #[Serializer\SerializedName("date_debut_Mandat")]
    private ?\DateTimeInterface $dateDebutMandat = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_show'])]
    #[Serializer\SerializedName("site_internet")]
    private ?string $siteInternet = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_show'])]
    private ?string $twitter = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_show'])]
    private ?string $facebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_show'])]
    private ?string $blog = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['regional_advisory_show'])]
    private ?string $linkedin = null;

    #[ORM\OneToOne(inversedBy: 'regionalAdvisory', cascade: ['persist', 'remove'])]
    #[Serializer\Groups(['regional_advisory_list', 'regional_advisory_show'])]
    
    private ?Adress $adress = null;
    
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

    public function setVilleNaissance(?string $villeNaissance): static
    {
        $this->villeNaissance = $villeNaissance;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
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

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getMandature(): ?string
    {
        return $this->mandature;
    }

    public function setMandature(?string $mandature): static
    {
        $this->mandature = $mandature;

        return $this;
    }

    public function isExecutif(): ?bool
    {
        return $this->executif;
    }

    public function setExecutif(?bool $executif): static
    {
        $this->executif = $executif;

        return $this;
    }

    public function isCommissionPermanente(): ?bool
    {
        return $this->commissionPermanente;
    }

    public function setCommissionPermanente(?bool $commissionPermanente): static
    {
        $this->commissionPermanente = $commissionPermanente;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDateDebutMandat(): ?\DateTimeInterface
    {
        return $this->dateDebutMandat;
    }

    public function setDateDebutMandat(?\DateTimeInterface $dateDebutMandat): static
    {
        $this->dateDebutMandat = $dateDebutMandat;

        return $this;
    }

    public function getSiteInternet(): ?string
    {
        return $this->siteInternet;
    }

    public function setSiteInternet(?string $siteInternet): static
    {
        $this->siteInternet = $siteInternet;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): static
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getBlog(): ?string
    {
        return $this->blog;
    }

    public function setBlog(?string $blog): static
    {
        $this->blog = $blog;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getListeElectorale(): ?string
    {
        return $this->listeElectorale;
    }

    public function setListeElectorale(?string $listeElectorale): static
    {
        $this->listeElectorale = $listeElectorale;

        return $this;
    }

}
