<?php

namespace App\Http\Controllers;

use App\Contracts\CheckoutTotalContract;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\CheckoutResource;
use App\Transformer\ProductTransformByProduct;
use App\Transformer\RuleTransformByProduct;

/**
 *
 */
class CheckoutController extends Controller
{

    /**
     * @param CheckoutRequest $checkoutRequest
     * @param CheckoutTotalContract $checkoutService
     * @return CheckoutResource
     */
    public function __invoke(CheckoutRequest $checkoutRequest, CheckoutTotalContract $checkoutService): CheckoutResource
    {

        $products = $checkoutRequest['products'];
        $orderItems = $checkoutRequest['orderItems'];

        $total = $checkoutService->getTotalPrice($products, $checkoutRequest['rules'], $orderItems);
        $stringOfItems = $checkoutService->getValidOrderItems($products, $orderItems);

        return new CheckoutResource(compact('total', 'stringOfItems'));
    }
}
