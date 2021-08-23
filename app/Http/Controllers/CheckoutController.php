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
        $transformedProductByProductName = new ProductTransformByProduct($checkoutRequest['products']);
        $transformedRuleByProductName = new RuleTransformByProduct($checkoutRequest['rules']);
        $orderItems = $checkoutRequest['orderItems'];

        $total = $checkoutService->getTotalPrice($transformedProductByProductName, $transformedRuleByProductName, $orderItems);
        $stringOfItems = $checkoutService->getValidOrderItems($transformedRuleByProductName, $orderItems);

        return new CheckoutResource(compact('total', 'stringOfItems'));
    }
}
