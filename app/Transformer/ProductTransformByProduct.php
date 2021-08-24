<?php

namespace App\Transformer;

use App\Contracts\TransformByProductContract;
use Illuminate\Support\Collection;


/**
 *
 */
class ProductTransformByProduct implements TransformByProductContract
{

    /**
     * @param array $items
     */
    public function __construct(protected array $products)
    {
    }

    /**
     * @return Collection
     */
    public function transformByProductName(): Collection
    {
        return collect($this->products)->keyBy('name');
    }
}
