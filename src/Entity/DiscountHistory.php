<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/24/18
 * Time: 9:41 PM
 */


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DiscountHistory
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
    private $orderId;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppliedDiscount", mappedBy="discountHistory")
     */
    private $appliedDiscount;

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
     * @return DiscountHistory
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return DiscountHistory
     */
    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return DiscountHistory
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAppliedDiscount(): ArrayCollection
    {
        return $this->appliedDiscount;
    }

    /**
     * @param ArrayCollection $appliedDiscount
     *
     * @return DiscountHistory
     */
    public function setAppliedDiscount(ArrayCollection $appliedDiscount): self
    {
        $this->appliedDiscount = $appliedDiscount;

        return $this;
    }
}
