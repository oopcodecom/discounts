<?php
declare(strict_types=1);
/**
 * Description: Discount Controller provides API entry point to calculate discount for order
 *                or to get an information about applied discounts
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Controller;

use App\Service\DiscountManager\DiscountManagerInterface;
use App\Service\SerializerFactory\SerializerFactoryInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class DiscountController
 */
class DiscountController extends FOSRestController
{
    /**
     * Calculate discount for customer order
     *
     * This call service which will calculate discount by order and customer order history
     *
     * @Route("/api/calculate-discount-for-order", methods={"POST"})
     *
     * @SWG\Post(
     *  consumes={"application/json"},
     *  produces={"application/json"},
     *  tags={"discounts"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="json order object",
     *              @SWG\Schema(
     *               type="object",
     *               required={"id", "customer-id", "items", "total"},
     *                  @SWG\Property(
     *                    type="string",
     *                    property="id",
     *                    example="1"
     *                  ),
     *                  @SWG\Property(
     *                    type="string",
     *                    property="customer-id",
     *                    example="1"
     *                  ),
     *                  @SWG\Property(
     *                    property="items",
     *                    type="array",
     *                      @SWG\Items(
     *                        type="object",
     *                        required={"product-id", "quantity", "unit-price", "total"},
     *                          @SWG\Property(property="product-id", type="string", example="B102"),
     *                          @SWG\Property(property="quantity", type="string", example="10"),
     *                          @SWG\Property(property="unit-price", type="string", example="4.99"),
     *                          @SWG\Property(property="total", type="string", example="49.90"),
     *                      ),
     *                   ),
     *                  @SWG\Property(
     *                    type="string",
     *                    property="total",
     *                    example="49.90"
     *                  ),
     *              )
     *      ),
     *     @SWG\Response(
     *     response=200,
     *     description="calculated discount for order"
     *     )
     * )
     *
     * @param Request                    $request
     *
     * @param DiscountManagerInterface   $discountManager
     * @param SerializerFactoryInterface $serializerFactory
     *
     * @return Response
     */
    public function calculateDiscountForOrderAction(Request $request, DiscountManagerInterface $discountManager, SerializerFactoryInterface $serializerFactory): Response
    {
        $discountHistory = $discountManager->getDiscountForOrder($request->getContent());

        $serializer = $serializerFactory->getSerializer();
        $serializedDiscount = $serializer->serialize($discountHistory, 'json');

        return new Response($serializedDiscount, 200);
    }
}
