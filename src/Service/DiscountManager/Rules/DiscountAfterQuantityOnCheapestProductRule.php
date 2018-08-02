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

        $productsForDiscount = $this->sortProductsForDiscount($order['items']);

        if (count($productsForDiscount) >= $this->ruleValue) {
            $discount = (float) $productsForDiscount[0]['total'];
            array_map(function ($product) use (&$discount) {
                if ((float) $product['total'] < $discount) {
                    $discount = (float) $product['total'];
                }
            }, $productsForDiscount);
        }

        return $discount;
    }

    /**
     * @param array $products
     *
     * @return array
     */
    private function sortProductsForDiscount(array $products): array
    {
        $productsForDiscount = [];

        foreach ($products as $product) {
            $productCategoryId = $this->apiClient->getProductCategoryId($product['product-id']);
            if ((string) $this->productCategoryId === $productCategoryId) {
                $productsForDiscount[] = $product;
            }
        }

        return $productsForDiscount;
    }
}
