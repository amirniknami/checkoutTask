<?php

namespace App\Transformer;

use App\Contracts\TransformByProductContract;
use Illuminate\Support\Collection;

/**
 *
 */
class RuleTransformByProduct implements TransformByProductContract
{
    /**
     * @param array $rules
     */
    public function __construct(protected array $rules)
    {
    }

    /**
     * @return Collection
     */
    public function transform(): Collection
    {
        return collect($this->rules)->keyBy('product');
    }
}
