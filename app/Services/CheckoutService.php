<?php

namespace App\Services;

use App\Contracts\CheckoutTotalContract;
use App\Contracts\TransformByProductContract;
use Illuminate\Support\Collection;

/**
 *
 */
class CheckoutService implements CheckoutTotalContract
{

    /**
     * @param TransformByProductContract $itemsByProductName
     * @param TransformByProductContract $rulesByProductName
     * @param array $orderItems
     * @return float
     */
    public function getTotalPrice(TransformByProductContract $itemsByProductName, TransformByProductContract $rulesByProductName, array $orderItems): float
    {

        $itemsByProductCount = collect($orderItems)->countBy('product');

        $applicableRules = $rulesByProductName->transform()
            ->intersectByKeys($itemsByProductCount);

        $productsByProductName = $itemsByProductName->transform();

        return
            (float)$itemsByProductCount->reduce(function ($carry, $count, $product) use ($applicableRules, $productsByProductName) {
                return $carry + $this->calculateItemPrice($applicableRules[$product] ?? null, $productsByProductName[$product] ?? null, $count, $product);
            });
    }


    /**
     * @param TransformByProductContract $itemsByProductName
     * @param array $orderItems
     * @return string
     */
    public function getValidOrderItems(TransformByProductContract $itemsByProductName, array $orderItems): string
    {
        $items = $itemsByProductName->transform();
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
