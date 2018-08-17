<?php
declare(strict_types=1);
/**
 * Description: Test for Discount Manager - responsible to apply discount for order and return
 *                serialized order history
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Tests\Service\DiscountManager;

use App\Entity\AppliedDiscount;
use App\Entity\Discount;
use App\Entity\DiscountHistory;
use App\Entity\Rule;
use App\Service\DiscountManager\DiscountManager;
use App\Service\DiscountRules\DiscountForCheapestProductFromProductsOfOneCategory;
use App\Service\DiscountRules\DiscountForEveryNextProductItemRule;
use App\Service\DiscountRules\DiscountOnCustomerSpentAmountRule;
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
    /** @var ObjectManager|Mockery\MockInterface $objectManagerMock */
    private $objectManagerMock;

    /** @var EntityManager|Mockery\MockInterface $entityManagerMock */
    private $entityManagerMock;

    /** @var SerializerClient|Mockery\MockInterface $serializerClientMock */
    private $serializerClientMock;

    /** @var Serializer|Mockery\MockInterface $serializerMock */
    private $serializerMock;

    /** @var DiscountManager|Mockery\MockInterface $discountManager */
    private $discountManager;

    /**
     * setUp test data
     */
    public function setUp()
    {
        $this->objectManagerMock = Mockery::mock(ObjectManager::class);
        $this->entityManagerMock = Mockery::mock(EntityManager::class);
        $this->serializerClientMock = Mockery::mock(SerializerClient::class);
        $this->serializerMock = Mockery::mock(Serializer::class);
        $this->discountManager = new DiscountManager($this->objectManagerMock, $this->serializerClientMock);
    }

    /**
     * @dataProvider provideOrderAndExpectedSingleDiscount
     *
     * @param array $providedData
     */
    public function testDiscountManagerShouldReturnSingleDiscountObjectForOrderObject(array $providedData): void
    {
        $orderArray = json_decode($providedData['order'], true);
        $ruleObject = new Rule();
        $ruleObject->setName($providedData['appliedDiscount']['rule']);
        $ruleObject->setId($providedData['appliedDiscount']['id']);

        $discountObject = new Discount();
        $discountObject->setId($providedData['appliedDiscount']['id']);
        $discountObject->setName($providedData['appliedDiscount']['name']);
        $discountObject->setDiscountRate($providedData['appliedDiscount']['amount']);
        $discountObject->setProductCategory($providedData['appliedDiscount']['productCategory']);
        $discountObject->setDiscountOrder($providedData['appliedDiscount']['discountPriority']);
        $discountObject->setRule($ruleObject);
        $discountObject->setRuleValue($providedData['appliedDiscount']['ruleValue']);
        $discountObject->setIsActive($providedData['appliedDiscount']['isActive']);

        $expectedDiscountHistory = new DiscountHistory();
        $expectedDiscountHistory->setOrderId($orderArray['id']);

        $appliedDiscount = new AppliedDiscount();
        $appliedDiscount->setDiscountAmount($providedData['appliedDiscount']['discountAmount']);
        $appliedDiscount->setDiscount($discountObject);
        $appliedDiscount->setDiscountHistory($expectedDiscountHistory);

        $expectedDiscountHistory->addAppliedDiscount($appliedDiscount);
        $expectedDiscountHistory->setTotalDiscountAmount($providedData['totalDiscount']);


        $this->objectManagerMock->shouldReceive('getRepository')->with(Discount::class)->andReturn($this->entityManagerMock);
        $this->objectManagerMock->shouldReceive('getRepository')->with(DiscountHistory::class)->andReturn($this->entityManagerMock);
        $this->entityManagerMock->shouldReceive('findOneBy')->withAnyArgs()->andReturn(null);
        $this->entityManagerMock->shouldReceive('findBy')->with(['isActive' => true])->andReturn($discountObject);
        $this->objectManagerMock->shouldReceive('persist')->twice();
        $this->objectManagerMock->shouldReceive('flush');

        $actualDiscountHistory = $this->discountManager->getDiscountForOrder($providedData['order']);
        $this->assertEquals($expectedDiscountHistory, $actualDiscountHistory, "DiscountManager should return discount history with single applied discount");
    }


    /**
     * @return array
     */
    public function provideOrderAndExpectedSingleDiscount(): array
    {
        $firstOrder = [
            'order' => "{
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
            }",
            'appliedDiscount' => [
                'name' => 'Discount for every product of category Switches (id 2), when customer buy five, customer get a sixth for free.',
                'amount' => 100,
                'id' => 2,
                'productCategory' => 2,
                'ruleValue' => 6,
                'discountPriority' => 2,
                'isActive' => true,
                'rule' => DiscountForEveryNextProductItemRule::class,
                'discountAmount' => 4.99,
            ],
            'totalDiscount' => 4.99,
        ];

        $secondOrder = [
            'order' => "{
              \"id\": \"2\",
              \"customer-id\": \"2\",
              \"items\": [
                {
                  \"product-id\": \"B102\",
                  \"quantity\": \"5\",
                  \"unit-price\": \"4.99\",
                  \"total\": \"24.95\"
                }
              ],
              \"total\": \"24.95\"
            }",
            'appliedDiscount' => [
                'name' => 'Discount for customer who has already bought for over € 1000, gets a discount of 10% on the whole order.',
                'amount' => 10,
                'id' => 1,
                'productCategory' => null,
                'ruleValue' => 1000,
                'discountPriority' => 1,
                'isActive' => true,
                'rule' => DiscountOnCustomerSpentAmountRule::class,
                'discountAmount' => 2.495,
            ],
            'totalDiscount' => 2.495,
        ];

        $thirdOrder = [
            'order' => "{
              \"id\": \"3\",
              \"customer-id\": \"3\",
              \"items\": [
                {
                  \"product-id\": \"A101\",
                  \"quantity\": \"2\",
                  \"unit-price\": \"9.75\",
                  \"total\": \"19.50\"
                },
                {
                  \"product-id\": \"A102\",
                  \"quantity\": \"1\",
                  \"unit-price\": \"49.50\",
                  \"total\": \"49.50\"
                }
              ],
              \"total\": \"69.00\"
            }",
            'appliedDiscount' => [
                'name' => 'Discount if customer buy two or more products of category Tools (id 1), he get a 20% discount on the cheapest product.',
                'amount' => 20,
                'id' => 3,
                'productCategory' => 1,
                'ruleValue' => 2,
                'discountPriority' => 3,
                'isActive' => true,
                'rule' => DiscountForCheapestProductFromProductsOfOneCategory::class,
                'discountAmount' => 3.9,
            ],
            'totalDiscount' => 3.9,
        ];

        return [
            [$firstOrder],
            [$secondOrder],
            [$thirdOrder],
        ];
    }

    /**
     * @dataProvider provideOrderAndExpectedMultipleDiscount
     *
     * @param array $providedData
     */
    public function testDiscountManagerShouldReturnMultipleDiscountObjectForOrderObject(array $providedData): void
    {
        $orderArray = json_decode($providedData['order'], true);

        $expectedDiscountHistory = new DiscountHistory();
        $expectedDiscountHistory->setOrderId($orderArray['id']);

        /** @var Discount[] $discountObjects */
        $discountObjects = [];
        foreach ($providedData['appliedDiscount'] as $discount) {
            $ruleObject = new Rule();
            $ruleObject->setName($discount['rule']);
            $ruleObject->setId($discount['id']);

            $discountObject = new Discount();
            $discountObject->setId($discount['id']);
            $discountObject->setName($discount['name']);
            $discountObject->setDiscountRate($discount['amount']);
            $discountObject->setProductCategory($discount['productCategory']);
            $discountObject->setDiscountOrder($discount['discountPriority']);
            $discountObject->setRule($ruleObject);
            $discountObject->setRuleValue($discount['ruleValue']);
            $discountObject->setIsActive($discount['isActive']);
            $discountObjects[] = $discountObject;

            $appliedDiscount = new AppliedDiscount();
            $appliedDiscount->setDiscountAmount($discount['discountAmount']);
            $appliedDiscount->setDiscount($discountObject);
            $appliedDiscount->setDiscountHistory($expectedDiscountHistory);

            $expectedDiscountHistory->addAppliedDiscount($appliedDiscount);
        }

        $expectedDiscountHistory->setTotalDiscountAmount($providedData['totalDiscount']);


        $this->objectManagerMock->shouldReceive('getRepository')->with(Discount::class)->andReturn($this->entityManagerMock);
        $this->objectManagerMock->shouldReceive('getRepository')->with(DiscountHistory::class)->andReturn($this->entityManagerMock);
        $this->entityManagerMock->shouldReceive('findOneBy')->withAnyArgs()->andReturn(null);
        $this->entityManagerMock->shouldReceive('findBy')->with(['isActive' => true])->andReturn($discountObjects);
        $this->objectManagerMock->shouldReceive('persist')->twice();
        $this->objectManagerMock->shouldReceive('flush');

        $actualDiscountHistory = $this->discountManager->getDiscountForOrder($providedData['order']);
        $this->assertEquals($expectedDiscountHistory, $actualDiscountHistory, "DiscountManager should return discount history with multiple applied discounts");
    }

    /**
     * @return array
     */
    public function provideOrderAndExpectedMultipleDiscount(): array
    {
        $orderForMultipleDiscounts = [
            'order' => "{
              \"id\": \"99\",
              \"customer-id\": \"2\",
              \"items\": [
                {
                  \"product-id\": \"B102\",
                  \"quantity\": \"10\",
                  \"unit-price\": \"4.99\",
                  \"total\": \"49.90\"
                },
                {
                  \"product-id\": \"B101\",
                  \"quantity\": \"100\",
                  \"unit-price\": \"4.99\",
                  \"total\": \"490.90\"
                },
                {
                  \"product-id\": \"A101\",
                  \"quantity\": \"2\",
                  \"unit-price\": \"9.75\",
                  \"total\": \"19.50\"
                },
                {
                  \"product-id\": \"A102\",
                  \"quantity\": \"1\",
                  \"unit-price\": \"49.50\",
                  \"total\": \"49.50\"
                }
            
              ],
              \"total\": \"609.8\"
            }",
            'appliedDiscount' => [
                [
                    'name' => 'Discount for customer who has already bought for over € 1000, gets a discount of 10% on the whole order.',
                    'amount' => 10,
                    'id' => 1,
                    'productCategory' => null,
                    'ruleValue' => 1000,
                    'discountPriority' => 1,
                    'isActive' => true,
                    'rule' => DiscountOnCustomerSpentAmountRule::class,
                    'discountAmount' => 60.98,
                ],
                [
                    'name' => 'Discount for every product of category Switches (id 2), when customer buy five, customer get a sixth for free.',
                    'amount' => 100,
                    'id' => 2,
                    'productCategory' => 2,
                    'ruleValue' => 6,
                    'discountPriority' => 2,
                    'isActive' => true,
                    'rule' => DiscountForEveryNextProductItemRule::class,
                    'discountAmount' => 84.83,
                ],
                [
                    'name' => 'Discount if customer buy two or more products of category Tools (id 1), he get a 20% discount on the cheapest product.',
                    'amount' => 20,
                    'id' => 3,
                    'productCategory' => 1,
                    'ruleValue' => 2,
                    'discountPriority' => 3,
                    'isActive' => true,
                    'rule' => DiscountForCheapestProductFromProductsOfOneCategory::class,
                    'discountAmount' => 3.9,
                ],
              ],
            'totalDiscount' => 149.71,
        ];

        return [
            [$orderForMultipleDiscounts],
        ];
    }

    /**
     * @dataProvider provideOrderWithAppliedDiscount
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @param array $providedData
     */
    public function testDiscountManagerWillNotProcessOrderWithExistingDiscountHistory(array $providedData): void
    {
        $orderArray = json_decode($providedData['order'], true);
        $ruleObject = new Rule();
        $ruleObject->setName($providedData['appliedDiscount']['rule']);
        $ruleObject->setId($providedData['appliedDiscount']['id']);

        $discountObject = new Discount();
        $discountObject->setId($providedData['appliedDiscount']['id']);
        $discountObject->setName($providedData['appliedDiscount']['name']);
        $discountObject->setDiscountRate($providedData['appliedDiscount']['amount']);
        $discountObject->setProductCategory($providedData['appliedDiscount']['productCategory']);
        $discountObject->setDiscountOrder($providedData['appliedDiscount']['discountPriority']);
        $discountObject->setRule($ruleObject);
        $discountObject->setRuleValue($providedData['appliedDiscount']['ruleValue']);
        $discountObject->setIsActive($providedData['appliedDiscount']['isActive']);

        $existingDiscountHistory = new DiscountHistory();
        $existingDiscountHistory->setOrderId($orderArray['id']);

        $appliedDiscount = new AppliedDiscount();
        $appliedDiscount->setDiscountAmount($providedData['appliedDiscount']['discountAmount']);
        $appliedDiscount->setDiscount($discountObject);
        $appliedDiscount->setDiscountHistory($existingDiscountHistory);

        $existingDiscountHistory->addAppliedDiscount($appliedDiscount);
        $existingDiscountHistory->setTotalDiscountAmount($providedData['totalDiscount']);
        $this->objectManagerMock->shouldReceive('getRepository')->with(Discount::class)->andReturn($this->entityManagerMock);
        $this->objectManagerMock->shouldReceive('getRepository')->with(DiscountHistory::class)->andReturn($this->entityManagerMock);
        $this->entityManagerMock->shouldReceive('findOneBy')->with(['orderId' => $orderArray['id']])->andReturn($existingDiscountHistory);
        $this->entityManagerMock->shouldReceive('findBy')->with(['isActive' => true])->andReturn($orderArray['id']);
        $this->objectManagerMock->shouldReceive('persist')->twice();
        $this->objectManagerMock->shouldReceive('flush');

        $this->discountManager->getDiscountForOrder($providedData['order']);
    }

    /**
     * @return array
     */
    public function provideOrderWithAppliedDiscount(): array
    {
        $orderWithAppliedDiscount = [
            'order' => "{
              \"id\": \"2\",
              \"customer-id\": \"2\",
              \"items\": [
                {
                  \"product-id\": \"B102\",
                  \"quantity\": \"5\",
                  \"unit-price\": \"4.99\",
                  \"total\": \"24.95\"
                }
              ],
              \"total\": \"24.95\"
            }",
            'appliedDiscount' => [
                'name' => 'Discount for customer who has already bought for over € 1000, gets a discount of 10% on the whole order.',
                'amount' => 10,
                'id' => 1,
                'productCategory' => null,
                'ruleValue' => 1000,
                'discountPriority' => 1,
                'isActive' => true,
                'rule' => DiscountOnCustomerSpentAmountRule::class,
                'discountAmount' => 2.495,
            ],
            'totalDiscount' => 2.495,
        ];

        return [
            [$orderWithAppliedDiscount],
        ];
    }
}
