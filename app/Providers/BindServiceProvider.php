<?php

namespace App\Providers;

use App\Contracts\CheckoutTotalContract;
use App\Services\CheckoutService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
      public array $bindings = [
          CheckoutTotalContract::class => CheckoutService::class
      ];
}
