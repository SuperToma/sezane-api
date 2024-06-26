<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['public:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['public:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 12, nullable: false)]
    #[Groups(['public:read'])]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 16, scale: 12, nullable: false)]
    #[Groups(['public:read'])]
    private ?float $longitude = null;

    #[Groups(['public:read'])]
    public ?float $distanceMeters = null;

    #[ORM\Column(length: 255)]
    #[Groups(['public:read'])]
    private ?string $address = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manager $manager = null;

    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'shop')]
    private Collection $stocks;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDistanceMeters(): ?float
    {
        return $this->distanceMeters;
    }

    public function setDistanceMeters(float $distanceMeters): static
    {
        $this->distanceMeters = $distanceMeters;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    #[Groups(['public:read'])]
    public function getManagerId(): int
    {
        return $this->getManager()->getId();
    }

    public function setManager(?Manager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setShop($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getShop() === $this) {
                $stock->setShop(null);
            }
        }

        return $this;
    }
}
