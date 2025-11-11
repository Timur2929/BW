<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TelegramAuthController extends Controller
{
    public function redirect()
    {
        $botUsername = config('services.telegram.bot_username');
        $redirectUrl = url('/auth/telegram/callback');
        
        $telegramUrl = "https://oauth.telegram.org/auth?bot_id={$botUsername}&origin={$redirectUrl}&embed=0&request_access=write";
        
        return redirect($telegramUrl);
    }

    public function callback(Request $request)
    {
        $authData = $request->all();
        
        // Проверяем подпись данных
        if (!$this->validateTelegramData($authData)) {
            return redirect('/login')->withErrors(['telegram' => 'Ошибка авторизации через Telegram']);
        }
        
        $telegramId = $authData['id'];
        $firstName = $authData['first_name'] ?? '';
        $lastName = $authData['last_name'] ?? '';
        $username = $authData['username'] ?? '';
        
        // Ищем пользователя или создаем нового
        $user = User::where('telegram_id', $telegramId)->first();
        
        if (!$user) {
            $user = User::create([
                'name' => $firstName . ($lastName ? " {$lastName}" : ''),
                'email' => $telegramId . '@telegram.user', // Временный email
                'password' => bcrypt(Str::random(16)),
                'telegram_id' => $telegramId,
                'telegram_username' => $username,
            ]);
        }
        
        Auth::login($user, true);
        
        return redirect('/');
    }
    
    private function validateTelegramData($data)
    {
        $botToken = config('services.telegram.bot_token');
        $checkHash = $data['hash'];
        unset($data['hash']);
        
        ksort($data);
        $dataCheckArr = [];
        foreach ($data as $key => $value) {
            $dataCheckArr[] = $key . '=' . $value;
        }
        
        $dataCheckString = implode("\n", $dataCheckArr);
        $secretKey = hash('sha256', $botToken, true);
        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);
        
        return strcmp($hash, $checkHash) === 0;
    }
}