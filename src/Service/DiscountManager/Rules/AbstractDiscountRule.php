<?php
declare(strict_types=1);
/**
 * Description: Discount Rule Abstraction contain main Rule data
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\DiscountManager\Rules;

use App\Service\RestClientStub\RestClientStub;

/**
 * Class AbstractDiscountRule
 */
abstract class AbstractDiscountRule
{
    /** @var mixed $ruleValue */
    protected $ruleValue;

    /** @var int $discountAmount */
    protected $discountAmount;

    /** @var int|null $productCategoryId */
    protected $productCategoryId;

    /** @var RestClientStub $apiClient */
    protected $apiClient;

    /**
     * DiscountRuleInterface constructor.
     *
     * @param mixed    $ruleValue
     * @param int      $discountAmount
     * @param int|null $productCategoryId
     */
    public function __construct($ruleValue, int $discountAmount, int $productCategoryId = null)
    {
        $this->ruleValue = $ruleValue;
        $this->discountAmount = $discountAmount;
        $this->productCategoryId = $productCategoryId;
        $this->apiClient = new RestClientStub();
    }
}
