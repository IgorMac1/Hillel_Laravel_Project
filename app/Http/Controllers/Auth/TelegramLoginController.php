<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use pschocke\TelegramLoginWidget\Facades\TelegramLoginWidget;

class TelegramLoginController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$telegramUser = TelegramLoginWidget::validate($request)) {
            notify()->error("Please try again", position: 'topRight');
            return redirect()->back();
        }

        if ($user = User::where('telegram_id', $telegramUser->get('id'))->first()) {
            Auth::guard()->login($user);

            $this->middleware('auth');
            $this->middleware('signed')->only('verify');

            return redirect()->route('account.index');
        }

        return redirect()->route('home');
    }
}
