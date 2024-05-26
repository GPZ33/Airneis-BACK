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
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    normalizationContext: ['enable_max_depth' => true,'groups' => ['product:read']],
    denormalizationContext: ['groups' => ['product:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Put(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ],
)]

class Product 
{
    #[Groups(["product:read","order_product:read","material:read","category:read","media_object:read"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["product:write","product:read"])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(["product:write","product:read"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Groups(["product:write","product:read"])]
    #[ORM\Column]
    private ?float $price = null;

    #[Groups(["product:write","product:read"])]
    #[ORM\Column]
    private ?bool $stock = null;

    #[Groups(["product:write","product:read"])]
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[MaxDepth(1)]
    private Collection $category;

    #[Groups(["product:write","product:read"])]
    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: Material::class, mappedBy: 'products')]
    private Collection $materials;

    #[Groups(["product:read"])]
    #[MaxDepth(1)]
    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'idProduct')]
    private Collection $orderProducts;

    #[Groups(["product:write","product:read"])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[\Symfony\Component\Serializer\Annotation\Context([
        DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
    ])]
    private ?\DateTimeInterface $addedDate = null;

    #[Groups(["product:write","product:read"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $details = null;

    /**
     * @var Collection<int, Images>
     */
    #[Groups(["product:read"])]
    #[MaxDepth(1)]
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'product')]
    private Collection $images;

    #[ORM\Column]
    private ?bool $isHighlander = false;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->materials = new ArrayCollection();
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

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->addedDate;
    }

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
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    #[Groups(["product:read"])]
    public function isHighlander(): ?bool
    {
        return $this->isHighlander;
    }

    public function setHighlander(?bool $isHighlander): static
    {
        $this->isHighlander = $isHighlander;

        return $this;
    }
}