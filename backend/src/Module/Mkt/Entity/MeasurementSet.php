<?php

namespace App\Module\Mkt\Entity;

use App\Module\Mkt\Enum\MeasurementSetStatus;
use App\Module\Mkt\Repository\MeasurementSetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MeasurementSetRepository::class)]
class MeasurementSet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['measurement_set:index', 'measurement_set:show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['measurement_set:index', 'measurement_set:show'])]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['measurement_set:index', 'measurement_set:show'])]
    private ?float $mkt = null;

    #[ORM\Column(type: Types::SMALLINT, enumType: MeasurementSetStatus::class)]
    #[Groups(['measurement_set:index', 'measurement_set:show'])]
    private MeasurementsetStatus $status = MeasurementSetStatus::InProgress;

    #[ORM\Column]
    #[Groups(['measurement_set:index', 'measurement_set:show'])]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Measurement>
     */
    #[ORM\OneToMany(targetEntity: Measurement::class, mappedBy: 'measurement_set', orphanRemoval: true)]
    private Collection $measurements;

    public function __construct()
    {
        $this->measurements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getMkt(): ?float
    {
        return $this->mkt;
    }

    public function setMkt(?float $mkt): static
    {
        $this->mkt = $mkt;

        return $this;
    }

    public function getStatus(): MeasurementSetStatus
    {
        return $this->status;
    }

    public function setStatus(MeasurementSetStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Measurement>
     */
    public function getMeasurements(): Collection
    {
        return $this->measurements;
    }

    public function addMeasurement(Measurement $measurement): static
    {
        if (!$this->measurements->contains($measurement)) {
            $this->measurements->add($measurement);
            $measurement->setMeasurementSet($this);
        }

        return $this;
    }

    public function removeMeasurement(Measurement $measurement): static
    {
        if ($this->measurements->removeElement($measurement)) {
            // set the owning side to null (unless already changed)
            if ($measurement->getMeasurementSet() === $this) {
                $measurement->setMeasurementSet(null);
            }
        }

        return $this;
    }
}
