<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Exception\TelegramBotApiException;
use Illuminate\Support\Facades\Http;
use Throwable;

class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';
    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            $response = Http::get(self::HOST . $token . '/sendMessage', [
                'text' => $text,
                'chat_id' => $chatId,
            ])->throw()->json();

            return $response['ok'] ?? false;
        } catch (Throwable $exception) {
            report(new TelegramBotApiException($exception->getMessage()));

            return false;
        }

    }
}
