<?php
declare(strict_types = 1);

namespace api\modules\v1\controllers;

use api\modules\v1\components\Telegram;
use api\modules\v1\controllers\base\Controller;
use TelegramBot\Api\Client;

class TelegramController extends Controller
{
    public function actionMessage()
    {
        $telegram = new Telegram();
        $bot = new Client($_SERVER['TOKEN']);

        $telegram->command($bot, 'start');
        $telegram->command($bot, 'help');
        $telegram->command($bot, '?');
        $telegram->command($bot, 'home', true);
        $telegram->command($bot, 'ill', true);
        $telegram->command($bot, 'vacation', true);
        $telegram->command($bot, 'dayoff', true);
        $telegram->message($bot);

        $bot->run();
    }
}

