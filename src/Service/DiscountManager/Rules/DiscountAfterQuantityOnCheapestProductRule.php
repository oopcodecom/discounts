<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 8/1/18
 * Time: 8:11 AM
 */

namespace App\Service\DiscountManager\Rules;

/**
 * Class DiscountAfterQuantityOnCheapestProductRule
 */
class DiscountAfterQuantityOnCheapestProductRule extends AbstractDiscountRule implements DiscountRuleInterface
{
    /**
     * @param array $order
     *
     * @return float
     */
    public function calculateDiscount(array $order): float
    {
        $discount = 0.00;

        foreach ($order['items'] as $item) {

        }

        return $discount;
    }
}
