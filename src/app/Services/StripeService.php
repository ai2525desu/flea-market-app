<?php

namespace App\Services;

use Stripe\Checkout\Session;

class StripeService
{
    public function createSession(array $data)
    {
        return Session::create($data);
    }
}
