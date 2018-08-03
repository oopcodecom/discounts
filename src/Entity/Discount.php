<?php
declare(strict_types=1);
/**
 * Description: Discount entity responsible for storing configurable discounts
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiscountRepository")
 */
class Discount
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $discountRate;

    /**
     * @var null|int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productCategory;

    /**
     * @Serializer\Exclude()
     *
     * @var Rule
     *
     * @ORM\ManyToOne(targetEntity="Rule", inversedBy="discounts")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     */
    private $rule;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $ruleValue;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $discountOrder;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @Serializer\Exclude()
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppliedDiscount", mappedBy="discount")
     */
    private $appliedDiscounts;

    /**
     * Discount constructor
     */
    public function __construct()
    {
        $this->appliedDiscounts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return Discount
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Discount
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getDiscountRate(): int
    {
        return $this->discountRate;
    }

    /**
     * @param mixed $discountRate
     *
     * @return Discount
     */
    public function setDiscountRate(int $discountRate): self
    {
        $this->discountRate = $discountRate;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getProductCategory(): ?int
    {
        return $this->productCategory;
    }

    /**
     * @param null|int $productCategory
     *
     * @return Discount
     */
    public function setProductCategory($productCategory): self
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    /**
     * @return Rule
     */
    public function getRule(): Rule
    {
        return $this->rule;
    }

    /**
     * @param Rule $rule
     *
     * @return Discount
     */
    public function setRule(Rule $rule): self
    {
        $this->rule = $rule;

        return $this;
    }

    /**
     * @return int
     */
    public function getDiscountOrder(): int
    {
        return $this->discountOrder;
    }

    /**
     * @param int $discountOrder
     *
     * @return Discount
     */
    public function setDiscountOrder(int $discountOrder): self
    {
        $this->discountOrder = $discountOrder;

        return $this;
    }

    /**
     * @return int
     */
    public function getRuleValue(): int
    {
        return $this->ruleValue;
    }

    /**
     * @param int $ruleValue
     *
     * @return Discount
     */
    public function setRuleValue(int $ruleValue): self
    {
        $this->ruleValue = $ruleValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return Discount
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAppliedDiscount(): Collection
    {
        return $this->appliedDiscounts;
    }

    /**
     * @param AppliedDiscount $appliedDiscounts
     *
     * @return Discount
     */
    public function addAppliedDiscount(AppliedDiscount $appliedDiscounts): self
    {
        $appliedDiscounts->setDiscount($this);
        $this->appliedDiscounts[] = $appliedDiscounts;

        return $this;
    }

    /**
     * @param ArrayCollection $appliedDiscounts
     *
     * @return Discount
     */
    public function addAppliedDiscounts(ArrayCollection $appliedDiscounts): self
    {
        foreach ($appliedDiscounts as $appliedDiscount) {
            $this->addAppliedDiscount($appliedDiscount);
        }

        return $this;
    }
}
