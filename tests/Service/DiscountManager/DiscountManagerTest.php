<?php
declare(strict_types=1);
/**
 * Description: Test Discount Manager responsible to apply discount for order and return
 *                serialized order history
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Tests\Service\DiscountManager;

use App\Entity\Discount;
use App\Entity\DiscountHistory;
use App\Entity\Rule;
use App\Service\DiscountManager\DiscountManager;
use App\Service\DiscountManager\Rules\DiscountForCheapestProductFromProductsOfOneCategory;
use App\Service\DiscountManager\Rules\DiscountForEveryNextProductItemRule;
use App\Service\DiscountManager\Rules\DiscountOnCustomerSpentAmountRule;
use App\Service\SerializerClient\SerializerClient;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Class DiscountManagerTest
 */
class DiscountManagerTest extends TestCase
{
    /** @var ObjectManager $objectManagerMock */
    private $objectManagerMock;

    /** @var EntityManager $entityManagerMock */
    private $entityManagerMock;

    /** @var SerializerClient $serializerClientMock */
    private $serializerClientMock;

    /** @var Serializer $serializerMock */
    private $serializerMock;

    /** @var DiscountManager $discountManagerMock */
    private $discountManagerMock;

    /** @var Discount[] $discountObjects */
    private $discountObjects;

    /**
     * setUp test data
     */
    public function setUp()
    {
        $this->objectManagerMock = Mockery::mock(ObjectManager::class);
        $this->entityManagerMock = Mockery::mock(EntityManager::class);
        $this->serializerClientMock = Mockery::mock(SerializerClient::class);
        $this->serializerMock = Mockery::mock(Serializer::class);
        $this->discountManagerMock = new DiscountManager($this->objectManagerMock, $this->serializerClientMock);
        $this->generateDiscounts();
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

        $this->objectManagerMock->shouldReceive('getRepository')->with(Discount::class)->andReturn($this->entityManagerMock);
        $this->objectManagerMock->shouldReceive('getRepository')->with(DiscountHistory::class)->andReturn($this->entityManagerMock);
        $this->entityManagerMock->shouldReceive('findOneBy')->withAnyArgs()->andReturn(null);
        $this->entityManagerMock->shouldReceive('findBy')->with(['isActive' => true])->andReturn($this->discountObjects);
        $this->objectManagerMock->shouldReceive('persist')->withAnyArgs();
        $this->objectManagerMock->shouldReceive('flush')->withAnyArgs();
        $this->serializerClientMock->shouldReceive('getSerializer')->andReturn($this->serializerMock);
       // $this->serializerMock->shouldReceive('serialize')->with($discountHistory, 'json')->andReturn();

        // $this->discountManagerMock->shouldReceive('getDiscountForOrder')->with($order)->andReturn($appliedDiscount);

        $result = $this->discountManagerMock->getDiscountForOrder($order);
        $this->assertEquals($appliedDiscount, $result, "DiscountManager expected result with actual");
    }

    private function generateDiscounts(): void
    {
        $discounts  =  [
                [
                    'name' => 'Discount for customer who has already bought for over € 1000, gets a discount of 10% on the whole order.',
                    'amount' => 10,
                    'productCategory' => null,
                    'ruleValue' => 1000,
                    'discountPriority' => 1,
                    'isActive' => true,
                    'rule' => DiscountOnCustomerSpentAmountRule::class,
                ],
                [
                    'name' => 'Discount for every product of category Switches (id 2), when customer buy five, customer get a sixth for free.',
                    'amount' => 100,
                    'productCategory' => 2,
                    'ruleValue' => 6,
                    'discountPriority' => 2,
                    'isActive' => true,
                    'rule' => DiscountForEveryNextProductItemRule::class,
                ],
                [
                    'name' => 'Discount if customer buy two or more products of category Tools (id 1), he get a 20% discount on the cheapest product.',
                    'amount' => 20,
                    'productCategory' => 1,
                    'ruleValue' => 2,
                    'discountPriority' => 3,
                    'isActive' => true,
                    'rule' => DiscountForCheapestProductFromProductsOfOneCategory::class,
                ],
        ];

        foreach ($discounts as $discount) {
            $ruleObject = new Rule();
            $ruleObject->setName($discount['rule']);

            $discountObject = new Discount();
            $discountObject->setName($discount['name']);
            $discountObject->setDiscountRate($discount['amount']);
            $discountObject->setProductCategory($discount['productCategory']);
            $discountObject->setDiscountOrder($discount['discountPriority']);
            $discountObject->setRule($ruleObject);
            $discountObject->setRuleValue($discount['ruleValue']);
            $discountObject->setIsActive($discount['isActive']);

            $this->discountObjects[] = $discountObject;
        }
    }
}
