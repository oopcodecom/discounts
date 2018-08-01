<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 8/1/18
 * Time: 8:35 PM
 */

namespace App\Service\RestClientStub;

/**
 * Class RestClientStub
 */
class RestClientStub
{
    /**
     * @param string $customerId
     *
     * @return float
     */
    public function getCustomerSpentAmountByCustomerId(string $customerId): float
    {
        $spentAmount = 0.00;

        switch ($customerId) {
            case "1":
                $spentAmount = 1205.05;
                break;
            case "2":
                $spentAmount = 800.05;
                break;
            case "3":
                $spentAmount = 1;
                break;
            default:
                $spentAmount = 1001;
        }

        return $spentAmount;
    }

    /**
     * @param string $productId
     *
     * @return string
     */
    public function getProductCategoryId(string $productId): string
    {
        $categoryId = "0";

        switch ($productId) {
            case "A101":
                $categoryId = "1";
                break;
            case "B101":
                $categoryId = "2";
                break;
            default:
                $categoryId = "2";
        }

        return $categoryId;
    }
}
