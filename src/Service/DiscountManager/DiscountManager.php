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
use App\Service\DiscountRules\DiscountRuleInterface;
use App\Service\SerializerClient\SerializerClient;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class DiscountManager
 */
class DiscountManager implements DiscountManagerInterface
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
     * @return DiscountHistory
     */
    public function getDiscountForOrder(string $order): DiscountHistory
    {
        $orderArray = $this->deserializeOrder($order);
        $this->validateOrder($orderArray['id']);

        /** @var EntityRepository $discountRepository */
        $discountRepository = $this->objectManager->getRepository(Discount::class);
        $activeDiscountsResult = $discountRepository->findBy(['isActive' => true]);
        $activeDiscounts = is_array($activeDiscountsResult) ? $activeDiscountsResult : array($activeDiscountsResult);

        $discountHistory = new DiscountHistory();
        $discountHistory->setOrderId($orderArray['id']);

        $totalDiscount = 0.00;

        /** @var Discount $discount */
        foreach ($activeDiscounts as $discount) {
            /** @var Rule $ruleObject */
            $ruleObject = $discount->getRule();
            $ruleName = $ruleObject->getName();

            /** @var DiscountRuleInterface $discountRule */
            $discountRule = new $ruleName($discount->getRuleValue(), $discount->getDiscountRate(), $discount->getProductCategory());

            $calculatedDiscount = $discountRule->calculateDiscount($orderArray);

            if ($calculatedDiscount) {
                $totalDiscount += $calculatedDiscount;

                $appliedDiscount = new AppliedDiscount();
                $appliedDiscount->setDiscountAmount($calculatedDiscount);
                $appliedDiscount->setDiscount($discount);
                $appliedDiscount->setDiscountHistory($discountHistory);
                $this->objectManager->persist($appliedDiscount);

                $discountHistory->addAppliedDiscount($appliedDiscount);
            }
        }

        $discountHistory->setTotalDiscountAmount($totalDiscount);
        $this->objectManager->persist($discountHistory);
        $this->objectManager->flush();


        return $discountHistory;
    }



    /**
     * Note: This is only a stub, in real case $order should be deserialized
     *       in Order Object with fields, getters and setters.
     *
     * @param string $order
     *
     * @return array
     */
    private function deserializeOrder(string $order): array
    {
        $orderArray = json_decode($order, true);

        return $orderArray;
    }

    /**
     * @param string $orderId
     */
    private function validateOrder(string $orderId): void
    {
        /** @var EntityRepository $discountHistoryRepository */
        $discountHistoryRepository = $this->objectManager->getRepository(DiscountHistory::class);

        $appliedDiscountForOrder = $discountHistoryRepository->findOneBy(['orderId' => $orderId]);

        if ($appliedDiscountForOrder instanceof DiscountHistory) {
            throw new HttpException(406, 'Discount was already applied for this order');
        }
    }
}
