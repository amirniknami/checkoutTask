<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 *
 */
interface TransformByProductContract
{
    /**
     * @return Collection
     */
    public function transform(): Collection;
}
