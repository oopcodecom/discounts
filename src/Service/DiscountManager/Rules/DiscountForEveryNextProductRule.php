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
 * Class DiscountForEveryNextProductRule
 */
class DiscountForEveryNextProductRule extends AbstractDiscountRule implements DiscountRuleInterface
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
            $productId = $this->apiClient->getProductCategoryId($item['product-id']);
            if ((string) $this->productCategoryId === $productId) {
                $countItemsForDiscount = (int) ($item['quantity'] / $this->ruleValue);
                if ($countItemsForDiscount) {
                    $discount = (((float) $item['unit-price'] * $countItemsForDiscount) * $this->discountAmount) / 100;
                }
            }
        }

        return $discount;
    }
}
