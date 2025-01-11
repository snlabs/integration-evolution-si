<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;


#[ORM\Entity(repositoryClass: RegionRepository::class)]

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "region_show",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = { "regions_list" })
 * )
 * 
 * @Hateoas\Relation(
 *     "regions",
 *     href = @Hateoas\Route(
 *         "region_list",
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups = { "regions_show" })
 * )
 *
 * @Hateoas\Relation(
 *     "regional_advisories",
 *     href = @Hateoas\Route(
 *         "region_advisories",
 *         parameters = { "id" = "expr(object.getId())" }
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups = { "regions_list" })
 * )
 *
 */
class Region
{
    
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[Serializer\Groups(['regions_list', 'regions_show'])]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Groups(['regions_list', 'regions_show'])]
    #[Serializer\SerializedName("dep_election")]
    private ?string $depElection = null;

    /**
     * @var Collection<int, RegionalAdvisory>
     */
    #[ORM\OneToMany(targetEntity: RegionalAdvisory::class, mappedBy: 'region', cascade: ['persist'])]
    private Collection $regionalAdvisories;

    public function __construct()
    {
        $this->regionalAdvisories = new ArrayCollection();
    }
 
    public function getId(): int|null
    {
        return $this->id;
    }


    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }


    public function getDepElection(): ?string
    {
        return $this->depElection;
    }

    public function setDepElection(string $depElection): static
    {
        $this->depElection = $depElection;

        return $this;
    }

    /**
     * @return Collection<int, RegionalAdvisory>
     */
    public function getRegionalAdvisories(): Collection
    {
        return $this->regionalAdvisories;
    }

    public function addRegionalAdvisory(RegionalAdvisory $regionalAdvisory): static
    {
        if (!$this->regionalAdvisories->contains($regionalAdvisory)) {
            $this->regionalAdvisories->add($regionalAdvisory);
            $regionalAdvisory->setRegion($this);
        }

        return $this;
    }

    public function removeRegionalAdvisory(RegionalAdvisory $regionalAdvisory): static
    {
        if ($this->regionalAdvisories->removeElement($regionalAdvisory)) {
            // set the owning side to null (unless already changed)
            if ($regionalAdvisory->getRegion() === $this) {
                $regionalAdvisory->setRegion(null);
            }
        }

        return $this;
    }
  
}
