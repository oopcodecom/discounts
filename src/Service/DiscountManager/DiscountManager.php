<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/31/18
 * Time: 9:07 PM
 */

namespace App\Service\DiscountManager;

class DiscountManager
{
    public function getDiscontForOrder(string $order) {
        $orderObject = json_decode($order);
        return $orderObject;
    }
}