<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 8/1/18
 * Time: 8:58 PM
 */

namespace App\Service\DiscountManager\Rules;

use App\Service\RestClientStub\RestClientStub;

/**
 * Class AbstractDiscountRule
 */
abstract class AbstractDiscountRule
{
    /**
     * @var mixed|mixed
     */
    protected $ruleValue;

    /**
     * @var int
     */
    protected $discountAmount;

    /**
     * @var int|null
     */
    protected $productCategoryId;

    /** @var RestClientStub */
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
