<?php
declare(strict_types=1);
/**
 * Description: Discount Manager responsible to apply discount for order and return
 *                serialized order history
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\DiscountManager;

use App\Entity\AppliedDiscount;
use App\Entity\Discount;
use App\Entity\DiscountHistory;
use App\Entity\Rule;
use App\Repository\DiscountRepository;
use App\Service\DiscountManager\Rules\DiscountRuleInterface;
use App\Service\SerializerClient\SerializerClient;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class DiscountManager
 */
class DiscountManager
{
    /** @var ObjectManager $objectManager */
    private $objectManager;

    /** @var SerializerClient $serializer */
    private $serializer;

    /**
     * DiscountManager constructor.
     *
     * @param ObjectManager    $manager
     * @param SerializerClient $serializer
     */
    public function __construct(ObjectManager $manager, SerializerClient $serializer)
    {
        $this->objectManager = $manager;
        $this->serializer = $serializer;
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
         * @var array $orderArray
         */
        $orderArray = json_decode($order, true);


        /** @var DiscountRepository $discountRepository */
        $discountRepository = $this->objectManager->getRepository(Discount::class);
        $activeDiscounts = $discountRepository->findBy(['isActive' => true]);


        $discountHistory = new DiscountHistory();
        $discountHistory->setOrderId($orderArray['id']);

        $calculatedDiscount = 0.0;

        /** @var Discount $discount */
        foreach ($activeDiscounts as $discount) {
            /** @var Rule $ruleObject */
            $ruleObject = $discount->getRule();
            $ruleName = $ruleObject->getName();

            /** @var DiscountRuleInterface $discountRule */
            $discountRule = new $ruleName(
                $discount->getRuleValue(),
                $discount->getAmount(),
                $discount->getProductCategory()
            );

            $calculatedDiscount += $discountRule->calculateDiscount($orderArray);

            if ($calculatedDiscount) {
                $appliedDiscount = new AppliedDiscount();
                $appliedDiscount->setDiscountAmount($calculatedDiscount);
                $appliedDiscount->setDiscount($discount);
                $appliedDiscount->setDiscountHistory($discountHistory);
                $this->objectManager->persist($appliedDiscount);

                $discountHistory->addAppliedDiscount($appliedDiscount);
            }

        }

        $discountHistory->setTotalDiscountAmount($calculatedDiscount);
        $this->objectManager->persist($discountHistory);
        $this->objectManager->flush();
        $orderJson = $this->serializer->getSerializer()->serialize($discountHistory, 'json');

        return $orderJson;
    }
}