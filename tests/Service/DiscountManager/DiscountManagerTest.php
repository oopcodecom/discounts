<?php
declare(strict_types=1);
/**
 * Description: Test Discount Manager responsible to apply discount for order and return
 *                serialized order history
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Tests\Service\DiscountManager;

use App\Service\DiscountManager\DiscountManager;
use Doctrine\Common\Persistence\ObjectManager;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Class DiscountManagerTest
 */
class DiscountManagerTest extends TestCase
{
    /** @var ObjectManager $objectManagerMock */
    private $objectManagerMock;

    /** @var DiscountManager $discountManagerMock */
    private $discountManagerMock;

    /**
     * setUp test data
     */
    public function setUp()
    {
        $this->objectManagerMock = Mockery::mock(ObjectManager::class);
        $this->discountManagerMock = Mockery::mock(DiscountManager::class);
    }

    public function testDiscountManagerShouldReturnDiscountObjectForOrderObject(): void
    {
        $order = "{
         \"id\": \"1\",
         \"customer-id\": \"1\",
         \"items\": [
         {
            \"product-id\": \"B102\",
            \"quantity\": \"10\",
            \"unit-price\": \"4.99\",
            \"total\": \"49.90\"
         }       
        ],
         \"total\": \"49.90\"
        }";

        $appliedDiscount = "{
        \"id\":\"2\",
        \"order_id\":\"2\",
        \"total_discount_amount\":\"4.99\",
        \"applied_discounts\":[{
          \"id\":\"2\",
          \"discount\":{
            \"id\":\"5\",
            \"name\":\"Discount for every product of category Switches (id 2), when customer buy five, customer get a sixth for free.\",
            \"discount_rate\":\"100\",
            \"product_category\":\"2\",
            \"rule_value\":\"6\",
            \"discount_priority\":\"2\",
            \"is_active\":true},
            \"discount_amount\":\"4.99\"
            }]
        }";

        $this->discountManagerMock->shouldReceive('getDiscountForOrder')->with($order)->andReturn($appliedDiscount);

        $result = $this->discountManagerMock->getDiscountForOrder($order);
        $this->assertEquals($appliedDiscount, $result, "DiscountManager expected result with actual");
    }
}
