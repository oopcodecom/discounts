<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/31/18
 * Time: 9:07 PM
 */

namespace App\Service\DiscountManager;

use App\Entity\Discount;
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
        $orderObject = json_decode($order);

        $result = 0.0;

        /** @var DiscountRepository $discountRepository */
        $discountRepository = $this->objectManager->getRepository(Discount::class);
        $activeDiscounts = $discountRepository->findOrderedActiveDiscountsWithRuleNames();

        foreach ($activeDiscounts as $discount) {
            /** @var DiscountRuleInterface $ruleObject */
            $ruleObject = new $discount['ruleName'](
                $discount['ruleValue'],
                $discount['amount'],
                $discount['productCategory']
            );

            $result += $ruleObject->calculateDiscount($orderObject);

        }

        return $result;
    }
}