<?php
namespace App\Contracts;

/**
 *
 */
interface CheckoutTotalContract
{


    /**
     * @param array $productsList
     * @param array $rules
     * @param array $orderItems
     * @return float
     */
    public function getTotalPrice(array $productsList,
                                  array $rules,
                                  array $orderItems): float;


    /**
     * @param array $productsList
     * @param array $orderItems
     * @return string
     */
    public function getValidOrderItems(array $productsList, array $orderItems): string;
}


