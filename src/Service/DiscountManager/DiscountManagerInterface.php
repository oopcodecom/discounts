<?php
declare(strict_types=1);
/**
 * Description: Discount Manager interface
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\DiscountManager;

use App\Entity\DiscountHistory;

interface DiscountManagerInterface
{
    public function getDiscountForOrder(string $order): DiscountHistory;
}
