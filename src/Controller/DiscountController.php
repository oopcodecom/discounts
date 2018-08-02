<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/24/18
 * Time: 9:41 PM
 */

namespace App\Controller;

use App\Service\DiscountManager\DiscountManager;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Swagger\Annotations\Property;
use Swagger\Annotations\Schema;

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
     * @param Request $request
     *
     * @return Response
     */
    public function calculateDiscountForOrderAction(Request $request): Response
    {
        $discountManager = $this->container->get(DiscountManager::class);
        $discountJson = $discountManager->getDiscountForOrder($request->getContent());

        return new Response($discountJson, 200);
    }
}
