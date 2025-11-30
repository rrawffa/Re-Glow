<?php

namespace App\Models;

use App\Models\User;
use App\Models\PointTransaction;

class PointTraits
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function transactions()
    {
        return $this->user->pointTransactions();
    }

    public function currentBalance()
    {
        return $this->transactions()->sum('points');
    }

    public function totalEarned()
    {
        return $this->transactions()->where('type', 'earn')->sum('points');
    }

    public function totalRedeemed()
    {
        return $this->transactions()->where('type', 'redeem')->sum('points');
    }
}
