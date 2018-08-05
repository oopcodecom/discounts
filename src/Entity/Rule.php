<?php
declare(strict_types=1);
/**
 * Description: Rule entity responsible for storing available rules which can be applied for discount
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Rule
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
     * @ORM\Column(type="string", length=191)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Discount", mappedBy="rule")
     */
    private $discounts;

    /**
     * Rule constructor
     */
    public function __construct()
    {
        $this->discounts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Rule
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
     * @param string $name
     *
     * @return Rule
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDiscounts(): Collection
    {
        return $this->discounts;
    }

    /**
     * @param Discount $discount
     *
     * @return Rule
     */
    public function addDiscount(Discount $discount): self
    {
        $discount->setRule($this);
        $this->discounts[] = $discount;

        return $this;
    }

    /**
     * @param Discount $discounts
     *
     * @return Rule
     */
    public function addDiscounts(Discount $discounts): self
    {
        foreach ($discounts as $discount) {
            $this->addDiscount($discount);
        }

        return $this;
    }
}
