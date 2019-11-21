<?php

namespace api\modules\v1\components;

use common\models\{
    TelegramOrderHome,
    TelegramOrderIll,
    TelegramOrderDayoff,
    TelegramOrderVacation,
    User
};
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Telegram
{
    const LIST_COMMANDS =
        '/home - поработаю из дома' . PHP_EOL .
        '/ill - заболел и не могу работать' . PHP_EOL .
        '/vacation - хочу в отпуск' . PHP_EOL .
        '/dayoff - хочу взять отгул' . PHP_EOL .
        '/? - список всех команд';

    const KEYBOARD_DATE = [
        ['text' => 'Сегодня'],
        ['text' => 'Завтра'],
        ['text' => 'Указать дату']
    ];

    const KEYBOARD_SICK_LEAVE = [
        ['text' => 'С больничным листом'],
        ['text' => 'Без больничного листа']
    ];

    const KEYBOARD_DUE_TO_WHAT = [
        ['text' => 'Буду работать из дома'],
        ['text' => 'Без содержания'],
        ['text' => 'За счет отпуска'],
        ['text' => 'Отработаю в выходной']
    ];

    const KEYBOARD_DAYOFF = [
        ['text' => 'В счет отпуска'],
        ['text' => 'Отработаю позднее'],
        ['text' => 'За свой счет']
    ];

    const KEYBOARD_VACATION = [
        ['text' => 'Оплачиваемый'],
        ['text' => 'Неоплачиваемый'],
    ];

    public function createNewOrderHome($cid, $userId)
    {
        $model = new TelegramOrderHome(
            [
                'chat_id' => $cid,
                'user_id' => $userId,
                'date_absence' => null
            ]
        );
        $model->save();
    }

    public function createNewOrderIll($cid, $userId)
    {
        $model = new TelegramOrderIll(
            [
                'chat_id' => $cid,
                'user_id' => $userId,
                'sick_leave' => null,
                'due_to_what' => null,
                'planned_date_of_mining' => null
            ]
        );
        $model->save(false);
    }

    public function createNewOrderVacation($cid, $userId)
    {
        $model = new TelegramOrderVacation(
            [
                'chat_id' => $cid,
                'user_id' => $userId,
                'type_id' => null,
                'vacation_start' => null,
                'number_of_days' => null
            ]
        );
        $model->save();
    }

    public function createNewOrderDayoff($cid, $userId)
    {
        $model = new TelegramOrderDayoff(
            [
                'chat_id' => $cid,
                'user_id' => $userId,
                'dayoff_date' => null,
                'planned_date_of_mining' => null
            ]
        );
        $model->save();
    }

    public function getDateAbsenceOrderHome($userId, $dateAbsence)
    {
        return TelegramOrderHome::findOne(['user_id' => $userId, 'date_absence' => $dateAbsence]);
    }

    public function getDateAbsenceOrderDayoff($userId)
    {
        return TelegramOrderDayoff::findOne(['user_id' => $userId, 'dayoff_date' => null]);
    }

    public function checkForExistenceOrderHome($cid, $day)
    {
        $orderHome = TelegramOrderHome::findOne(['chat_id' => $cid, 'date_absence' => $day]);

        if ($orderHome) {
            return true;
        }
        return false;
    }

    public function checkForExistenceOrderDayoff($cid, $day)
    {
        $orderDayoff = TelegramOrderDayoff::find()->where(['chat_id' => $cid, 'dayoff_date' => $day])
            ->andWhere(['!=', 'type_id', null])->all();

        if ($orderDayoff) {
            return true;
        }
        return false;
    }

    public function chosseDay ($bot, $cid, $day, $keyboard)
    {
        $orderHome = TelegramOrderHome::findOne(['chat_id' => $cid, 'date_absence' => null]);
        $orderDayoff = TelegramOrderDayoff::findOne(['chat_id' => $cid, 'dayoff_date' => null]);

        if ($this->checkForExistenceOrderHome($cid, $day)
            || $this->checkForExistenceOrderDayoff($cid, $day)
        ) {

            if ($orderHome) {
                $orderHome->delete();
            }

            if ($orderDayoff) {
                $orderDayoff->delete();
            }

            $bot->sendMessage($cid, 'Заявка на эту дату уже существует', false, null, null);
            $bot->sendMessage($cid, self::LIST_COMMANDS, false, null, null);
        } else {
            if ($orderHome) {
                $orderHome->date_absence = $day;
                $orderHome->save();
                $bot->sendMessage($cid, 'Что случилось и почему из дома?', false, null, null);
            }

            if ($orderDayoff) {
                $orderDayoff->dayoff_date = $day;
                $orderDayoff->save();
                $bot->sendMessage($cid, 'Выберите способ взять отгул', false, null, null, $keyboard);
            }
        }
    }

    public function setTypeOfDayoff ($bot, $cid, $type_id, $text, $name = false)
    {
        $orderDayoff = TelegramOrderDayoff::findOne(['chat_id' => $cid, 'type_id' => null]);

        if ($orderDayoff) {
            $orderDayoff->type_id = $type_id;
            $orderDayoff->save();

            if ($name) {
                $bot->sendMessage($_SERVER['CHAT_ID_FOR_NOTIFICATION'], $name
                    . $text . $orderDayoff->dayoff_date, false, null, null);
                $bot->sendMessage($cid, 'Все ок', false, null, null);
            } else {
                $bot->sendMessage($cid, $text, false, null, null);
            }
        }
    }

    public function setTypeOfVacation ($bot, $cid, $type_id, $text)
    {
        $orderVacation = TelegramOrderVacation::findOne(['chat_id' => $cid, 'type_id' => null]);

        if ($orderVacation) {
            $orderVacation->type_id = $type_id;
            $orderVacation->save();

            $bot->sendMessage($cid, $text, false, null, null);
        }
    }

    public function sending_notification ($bot, $cid, $text)
    {
        $bot->sendMessage($_SERVER['CHAT_ID_FOR_NOTIFICATION'], $text, false, null, null);

        $bot->sendMessage($cid, 'Все ок, я сообщил кому надо', false, null, null);
    }

    public function setDueToWhat ($bot, $cid, $illType, $text = false)
    {
        $orderIll = TelegramOrderIll::find()
            ->andWhere(['chat_id' => $cid])
            ->andWhere(['sick_leave' => TelegramOrderIll::WITHOUT_SICK_LEAVE])
            ->andWhere(['due_to_what' => null])
            ->one();

        if ($orderIll) {
            $orderIll->due_to_what = $illType;
            $orderIll->save();

            if ($text) {
                $this->sending_notification ($bot, $cid, $text);
            }
        }
    }

    public function isHaveSickLeave ($cid, $sickLeave)
    {
        $orderIll = TelegramOrderIll::find()
            ->andWhere(['chat_id' => $cid])
            ->andWhere(['sick_leave' => null])
            ->one();

        if ($orderIll){
            $orderIll->sick_leave = $sickLeave;
            $orderIll->save();
        }
        return false;
    }


    public function command($bot, $command, $keyboard = false)
    {
        $bot->command($command, function ($message) use ($bot, $keyboard, $command) {
            $cid = $message->getChat()->getId();
            $name = $message->getChat()->getLastName() . ' ' . $message->getChat()->getFirstName();
            $modelUser = User::findOne(['name' => $name]);

            if ($keyboard) {
                $keyboardDate = new ReplyKeyboardMarkup([self::KEYBOARD_DATE], true, true);
                $keyboardVacation = new ReplyKeyboardMarkup([self::KEYBOARD_VACATION], true, true);
                $keyboardIll = new ReplyKeyboardMarkup([self::KEYBOARD_SICK_LEAVE], true, true);

                if ($command == 'home') {
                    $this->createNewOrderHome($cid, $modelUser->user_id);
                    $bot->sendMessage($cid, 'Когда планируете работать из дома?', false, null, null, $keyboardDate);
                }

                if ($command == 'ill') {
                    $this->createNewOrderIll($cid, $modelUser->user_id);
                    $bot->sendMessage($cid, 'Будете брать больничный?', false, null, null, $keyboardIll);
                }

                if ($command == 'vacation') {
                    $this->createNewOrderVacation($cid, $modelUser->user_id);
                    $bot->sendMessage($cid, 'Выберите тип отпуска', false, null, null, $keyboardVacation);
                }

                if ($command == 'dayoff') {
                    $this->createNewOrderDayoff($cid, $modelUser->user_id);
                    $bot->sendMessage($cid, 'На какую дату планируете взять отгул?', false, null, null, $keyboardDate);
                }
            } else {
                $bot->sendMessage($cid, self::LIST_COMMANDS, false, null, null);
            }
        });
    }

    public function message($bot)
    {
        $bot->on(function ($update) use ($bot) {
            $message = $update->getMessage();
            $name = $message->getChat()->getLastName() . ' ' . $message->getChat()->getFirstName();
            $mtext = $message->getText();
            $cid = $message->getChat()->getId();
            $today = date('d.m.Y');
            $tomorrow = date('d.m.Y', strtotime($today . ' + 1 days'));
            $keyboard_dayoff = new ReplyKeyboardMarkup([self::KEYBOARD_DAYOFF], true, true);
            $keyboard_due_to_what = new ReplyKeyboardMarkup([self::KEYBOARD_DUE_TO_WHAT], true, true);

            $modelUser = User::findOne(['name' => $name]);
            if (!$modelUser) {
                $model = new User(
                    [
                        'name' => $name,
                    ]
                );
                $model->save();
            }

            if ($mtext === 'Сегодня') {
                $this->chosseDay ($bot, $cid, $today, $keyboard_dayoff);
            }

            if ($mtext === 'Завтра') {
                $this->chosseDay ($bot, $cid, $tomorrow, $keyboard_dayoff);
            }

            if ($mtext === 'С больничным листом') {
                $this->isHaveSickLeave ($cid, TelegramOrderIll::WITH_SICK_LEAVE);

                $this->sending_notification ($bot, $cid, $modelUser->name
                    . ' заболел и не может работать, больничный лист предоставит');
            }

            if ($mtext === 'Без больничного листа') {
                $this->isHaveSickLeave ($cid, TelegramOrderIll::WITHOUT_SICK_LEAVE);
                $bot->sendMessage($cid, 'За счет чего берете больничный?', false, null, null, $keyboard_due_to_what);
            }

            if ($mtext === 'Буду работать из дома') {
                $this->setDueToWhat ($bot, $cid, TelegramOrderIll::I_WILL_WORK_FROM_HOME,
                    $modelUser->name . ' заболел и будет работать из дома');
            }

            if ($mtext === 'Без содержания') {
                $this->setDueToWhat ($bot, $cid, TelegramOrderIll::NO_CONTENT,
                    $modelUser->name . ' заболел и отлежиться за свой счет');
            }

            if ($mtext === 'За счет отпуска') {
                $this->setDueToWhat ($bot, $cid, TelegramOrderIll::IN_VACATION,
                    $modelUser->name . ' заболел и отлежиться за счет отпуска');
            }

            if ($mtext === 'Отработаю в выходной') {
                $this->setDueToWhat ($bot, $cid, TelegramOrderIll::ILL_WORK_AT_THE_WEEKEND);
                $bot->sendMessage($cid, 'Напишите дату планируемой отработки в формате дд.мм.гггг', false, null, null);
            }

            if ($mtext === 'Оплачиваемый') {
                $this->setTypeOfVacation ($bot, $cid, TelegramOrderVacation::PAID,
                    'Укажите дату начала отпуска в формате дд.мм.гггг');
            }

            if ($mtext === 'Неоплачиваемый') {
                $this->setTypeOfVacation ($bot, $cid, TelegramOrderVacation::UNPAID,
                    'Укажите дату начала отпуска в формате дд.мм.гггг');
            }

            if ($mtext === 'В счет отпуска') {
                $this->setTypeOfDayoff ($bot, $cid, TelegramOrderDayoff::ON_VACATION,
                    ' взял отгул в счет отпуска на дату ', $modelUser->name);
            }

            if ($mtext === 'За свой счет') {
                $this->setTypeOfDayoff ($bot, $cid, TelegramOrderDayoff::AT_OWN_EXPENSE,
                    ' взял отгул за свой счет на дату ', $modelUser->name);
            }

            if ($mtext === 'Отработаю позднее') {
                $this->setTypeOfDayoff ($bot, $cid, TelegramOrderDayoff::WORK_LATER,
                    'Напишите дату планируемой отработки в формате дд.мм.гггг');
            }

            if ($mtext === 'Указать дату') {
                $bot->sendMessage($cid, 'Напишите дату в формате дд.мм.гггг', false, null, null);
            }

            if (is_numeric($mtext) && $mtext > 0) {
                if ($mtext < 29) {
                    $orderVacation = TelegramOrderVacation::find()
                        ->andWhere(['chat_id' => $cid])
                        ->andWhere(['>','vacation_start', $today])
                        ->andWhere(['number_of_days' => null])
                        ->one();

                    if ($orderVacation) {
                        $orderVacation->number_of_days = $mtext;
                        $orderVacation->save();

                        $this->sending_notification ($bot, $cid, $modelUser->name
                            . ' хочет пойти в отпуск c ' . $orderVacation->vacation_start
                            . ' числа на ' . $orderVacation->number_of_days . ' дней');
                    }
                }
                 else {
                     $bot->sendMessage($cid, 'Отпуск не может быть более 28 дней', false, null, null);
                 }
            }

            if (is_numeric(strtotime($mtext)) && $mtext >= $today) {
                $orderIll = TelegramOrderIll::find()
                    ->andWhere(['chat_id' => $cid])
                    ->andWhere(['due_to_what' => TelegramOrderIll::ILL_WORK_AT_THE_WEEKEND])
                    ->andWhere(['planned_date_of_mining' => null])
                    ->one();

                $dayoffDateEmptyOrderDayoff = TelegramOrderDayoff::findOne(['chat_id' => $cid, 'dayoff_date' => null]);

                $orderVacation = TelegramOrderVacation::find()
                    ->andWhere(['chat_id' => $cid])
                    ->orWhere(['type_id'=> 1])
                    ->orWhere(['type_id'=> 2])
                    ->andWhere(['vacation_start' => null])
                    ->one();

                $orderDayoff = TelegramOrderDayoff::find()
                    ->andWhere(['chat_id' => $cid])
                    ->andWhere(['type_id' => TelegramOrderDayoff::WORK_LATER])
                    ->andWhere(['>=','dayoff_date', $today])
                    ->andWhere(['planned_date_of_mining' => null])
                    ->one();

                if ($orderIll) {
                    $orderIll->planned_date_of_mining = $mtext;
                    $orderIll->save();

                    $this->sending_notification ($bot, $cid, $modelUser->name
                        . ' заболелел, больничный брать не будет, с последующей отработкой '
                        . $orderIll->planned_date_of_mining . ' числа');
                }

                if ($orderVacation) {
                    $orderVacation->vacation_start = $mtext;
                    $orderVacation->save();

                    $bot->sendMessage($cid, 'Укажите длительность отпуска в днях', false, null, null);
                }

                if ($dayoffDateEmptyOrderDayoff) {
                    $dayoffDateEmptyOrderDayoff->dayoff_date = $mtext;
                    $dayoffDateEmptyOrderDayoff->save();

                    $bot->sendMessage($cid, 'Выберите способ взять отгул', false, null, null, $keyboard_dayoff);
                }

                if ($orderDayoff) {
                    $orderDayoff->planned_date_of_mining = $mtext;
                    $orderDayoff->save();

                    $this->sending_notification ($bot, $cid, $modelUser->name
                        . ' взял отгул с последующей отработкой ' . $orderDayoff->planned_date_of_mining
                        . ' числа на дату ' . $orderDayoff->dayoff_date);
                } else {
                    $existingOrderHome = TelegramOrderHome::findOne(['chat_id' => $cid, 'date_absence' => $mtext]);
                    $orderHome = TelegramOrderHome::findOne(['chat_id' => $cid, 'date_absence' => null]);

                    if (!$existingOrderHome) {
                        if ($orderHome) {
                            $orderHome->date_absence = $mtext;
                            $orderHome->save();

                            $bot->sendMessage($cid, 'Что случилось и почему из дома?', false, null, null);
                        }
                    } else {
                        $orderHome = TelegramOrderHome::findOne(['chat_id' => $cid, 'date_absence' => null]);

                        if ($orderHome) {
                            $orderHome->delete();
                        }

                        $bot->sendMessage($cid, 'Заявка на эту дату уже существует', false, null, null);
                        $bot->sendMessage($cid, self::LIST_COMMANDS, false, null, null);
                    }
                }
            }

            if (
                $mtext !== 'Сегодня'
                && $mtext !== 'Завтра'
                && $mtext !== 'Указать дату'
                && !is_numeric(strtotime($mtext))
            ) {
                $telegramOrderHome = TelegramOrderHome::findOne(['chat_id' => $cid, 'reason' => null]);

                if ($telegramOrderHome) {
                    $telegramOrderHome->reason = $mtext;
                    $telegramOrderHome->save();

                    $this->sending_notification ($bot, $cid, $modelUser->name
                        . ' хочет поработать из дома ' . $telegramOrderHome->date_absence
                        . ', потому что ' . $telegramOrderHome->reason);
                }
            }
        }, function ($update) {
            return true;
        });
    }
}