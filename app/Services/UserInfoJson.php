<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserInfoContract;

class UserInfoJson implements UserInfoContract
{
    public function generate()
    {
        $user = User::all()->random();
        return json_encode([
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
        ]);
    }
}

