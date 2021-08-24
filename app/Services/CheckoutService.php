<?php

namespace App\Services;

use App\Contracts\CheckoutTotalContract;
use App\Contracts\TransformByProductContract;
use App\Transformer\ProductTransformByProduct;
use App\Transformer\RuleTransformByProduct;
use Illuminate\Support\Collection;

/**
 *
 */
class CheckoutService implements CheckoutTotalContract
{


    /**
     * @param array $productsList
     * @param array $rules
     * @param array $orderItems
     * @return float
     */
    public function getTotalPrice(array $productsList, array $rules, array $orderItems): float
    {

        $itemsByProductCount = collect($orderItems)->countBy('product');


        $applicableRules = (new RuleTransformByProduct($rules))->transformByProductName()
            ->intersectByKeys($itemsByProductCount);


        $productsByProductName = (new ProductTransformByProduct($productsList))->transformByProductName();

        return
            (float)$itemsByProductCount->reduce(function ($carry, $count, $product) use ($applicableRules, $productsByProductName) {
                return $carry + $this->calculateItemPrice($applicableRules[$product] ?? null, $productsByProductName[$product] ?? null, $count, $product);
            });
    }


    /**
     * @param array $productsList
     * @param array $orderItems
     * @return string
     */
    public function getValidOrderItems(array $productsList, array $orderItems): string
    {
        $items = (new ProductTransformByProduct($productsList))->transformByProductName();
        return collect($orderItems)
            ->filter(fn($item) => $items->has($item['product']))
            ->map(fn($item) => $item['product'])
            ->implode(',');
    }


    /**
     * @param array|null $applicableRule
     * @param array|null $item
     * @param int $count
     * @param string $product
     * @return float
     */
    private function calculateItemPrice(?array $applicableRule, ?array $item, int $count, string $product): float
    {
        if (!$item) {
            return 0;
        }

        if (!$applicableRule) {
            return ($item['price'] * $count);
        }

        $applicableSpecialPriceCount = (int)($count / $applicableRule['quantities']);
        $applicableRegularPriceCount = ($count % $applicableRule['quantities']);

        return ($applicableSpecialPriceCount * $applicableRule['special_price']) + ($applicableRegularPriceCount * $item['price']);

    }


}
