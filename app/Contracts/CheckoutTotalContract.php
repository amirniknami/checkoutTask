<?php
namespace App\Contracts;

/**
 *
 */
interface CheckoutTotalContract
{

    /**
     * @param TransformByProductContract $itemsByProductName
     * @param TransformByProductContract $rulesByProductName
     * @param array $orderItems
     * @return float
     */
    public function getTotalPrice(TransformByProductContract $itemsByProductName,
                                  TransformByProductContract $rulesByProductName,
                                  array                      $orderItems): float;

    /**
     * @param TransformByProductContract $itemsByProductName
     * @param array $orderItems
     * @return string
     */
    public function getValidOrderItems(TransformByProductContract $itemsByProductName, array $orderItems): string;
}


