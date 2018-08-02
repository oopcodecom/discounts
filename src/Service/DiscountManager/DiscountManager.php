<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/31/18
 * Time: 9:07 PM
 */

namespace App\Service\DiscountManager;

use App\Entity\AppliedDiscount;
use App\Entity\Discount;
use App\Entity\DiscountHistory;
use App\Repository\DiscountRepository;
use App\Service\DiscountManager\Rules\DiscountRuleInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class DiscountManager
 */
class DiscountManager
{
    /** @var ObjectManager $objectManager */
    private $objectManager;

    /**
     * DiscountManager constructor.
     *
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->objectManager = $manager;
    }

    /**
     * @param string $order
     *
     * @return mixed
     */
    public function getDiscountForOrder(string $order)
    {
        /**
         * Note: This is only a stub, in real case $order should be deserialized
         *       in Order Object with fields, getters and setters.
         *
         * @var array $orderObject
         */
        $orderObject = json_decode($order, true);

        $calculatedDiscount = 0.0;

        /** @var DiscountRepository $discountRepository */
        $discountRepository = $this->objectManager->getRepository(Discount::class);
        $activeDiscounts = $discountRepository->findOrderedActiveDiscountsWithRuleNames();


        $discountHistory = new DiscountHistory();
        $discountHistory->setOrderId($order['id']);


        foreach ($activeDiscounts as $discount) {
            /** @var DiscountRuleInterface $ruleObject */
            $ruleObject = new $discount['ruleName'](
                $discount['ruleValue'],
                $discount['amount'],
                $discount['productCategory']
            );

            $calculatedDiscount = $ruleObject->calculateDiscount($orderObject);

            if($calculatedDiscount) {
                $appliedDiscount = new AppliedDiscount();
                $appliedDiscount->setAmount($calculatedDiscount);
                $appliedDiscount->setDiscount($discount);
                $appliedDiscount->setDiscountHistory($discountHistory);
                $this->objectManager->persist($appliedDiscount);
            }

        }

        $discountHistory->setTotal($total);
        $this->objectManager->persist($discountHistory);
        $this->objectManager->flush();

        return $calculatedDiscount;
    }
}