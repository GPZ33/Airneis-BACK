<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Category;
use App\Entity\Material;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Put(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ],
   // normalizationContext: ['groups' => ['read:collection']]
)]

class Product 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups(['read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
   // #[Groups(['read:collection'])]
    private ?string $name = null;

   
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    //#[Groups(['read:collection'])]
    private ?float $price = null;

    #[ORM\Column]
    //#[Groups(['read:collection'])]
    private ?bool $stock = null;
    //#[Groups(['read:out'])]
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $category;
    //#[Groups(['read:out'])]
    #[ORM\ManyToMany(targetEntity: Material::class, mappedBy: 'products')]
    private Collection $materials;
    //#[Groups(['read:out'])]
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'idProduct')]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'idProduct')]
    private Collection $orderProducts;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $addedDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $details = null;

    /**
     * @var Collection<int, Images>
     */
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'product')]
    private Collection $images;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isStock(): ?bool
    {
        return $this->stock;
    }

    public function setStock(bool $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Material>
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): static
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
            $material->addProduct($this);
        }

        return $this;
    }

    public function removeMaterial(Material $material): static
    {
        if ($this->materials->removeElement($material)) {
            $material->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setIdProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getIdProduct() === $this) {
                $order->setIdProduct(null);
            }
        }

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->addedDate;
    }
    // Création automatique 
    #[ORM\PrePersist]
    public function setAddedDate(\DateTimeInterface $addedDate): static
    {
        $this->addedDate = new \DateTime();
        return $this;
    }
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setIdProduct($this);
        }

        return $this;
    }


    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
    
            if ($orderProduct->getIdProduct() === $this) {
                $orderProduct->setIdProduct(null);
            }
        }

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $images): static
    {
        if (!$this->images->contains($images)) {
            $this->images->add($images);
            $images->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }
}