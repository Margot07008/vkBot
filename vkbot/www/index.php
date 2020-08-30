<?php

define('CALLBACK_API_EVENT_CONFIRMATION', 'confirmation');
define('CALLBACK_API_EVENT_MESSAGE_NEW', 'message_new');

require_once 'config.php';
require_once 'bot/bot.php';
require_once 'api/vk_api.php';

require_once 'bot/sendOnePred.php';

require_once '../connect.php';

const BTN_ONE_PRED = [["command"=>'newOne'], "🥠 Разломать печеньку", "blue"];
const BTN_YES = [["command"=>'yes'], "Да", "green"];
const BTN_NO = [["command"=>'no'], "Нет", "red"];
const BTN_DEL = [["command"=>'del'], "Скрыть", "white"];
const BTN_SUB = [["command"=>'sub'], "Подписаться", "green"];
const BTN_UNSUB = [["command"=>'unsub'], "Отписаться", "red"];

if (!isset($_REQUEST)) {
    exit;
}

callback_handleEvent();

function callback_handleEvent() {
    $event = _callback_getEvent();

    try {
        switch ($event['type']) {
            //Подтверждение сервера
            case CALLBACK_API_EVENT_CONFIRMATION:
                _callback_handleConfirmation();
                break;

            //Получение нового сообщения
            case CALLBACK_API_EVENT_MESSAGE_NEW:
                _callback_handleMessageNew($event['object']);
                break;

            default:
                _callback_response('Unsupported event');
                break;
        }
    } catch (Exception $e) {
//        log_error($e);
    }

    _callback_okResponse();
    mysqli_close($GLOBALS["mysql"]);
}

function _callback_getEvent() {
    return json_decode(file_get_contents('php://input'), true);
}

function _callback_handleConfirmation() {
    _callback_response(CALLBACK_API_CONFIRMATION_TOKEN);
}

function _callback_handleMessageNew($data) {



    if (mb_strtolower($data['text']) == 'начать')
    {
        sendButton($data['peer_id'], "Хочешь новое предсказание?", [[BTN_ONE_PRED]]);
    }
    else if (mb_strtolower($data['text']) == 'скрыть')
    {
        sendButton($data['peer_id'], "напиши начать", [[]]);
    }

    if (bot_userIsAssign($data['peer_id']))
    {
        sendButton($data['peer_id'], "",[BTN_ONE_PRED, BTN_UNSUB]);
    }
    else
    {
        sendButton($data['peer_id'],"", [BTN_ONE_PRED, BTN_SUB]);
    }



    if (isset($data["payload"])) {  //получаем payload
        $payload = json_decode($data["payload"], True); // Декодируем в JSON формат
    } else {
        $payload = null; // Иначе кнопок нет
    }

    $payload = $payload['command'];

    if ($payload == 'newOne')
    {
        vkApi_messagesSend($data['peer_id'], "Разламываю печеньку...");
        bot_sendMessageOnePrediction($data['peer_id']);
        sendButton($data['peer_id'], "Хочешь получать такие каждый день?", [[BTN_YES], [BTN_NO]]);
    }
    else if ($payload == 'yes')
    {
        bot_sendMessageAssing($data['peer_id']);
        sendButton($data['peer_id'], "Хочешь новое предсказание?", [[BTN_ONE_PRED]]);
    }
    else if ($payload == 'no')
    {
        bot_sendMessageUnsubscribe($data['peer_id']);
        sendButton($data['peer_id'], "Хочешь новое предсказание?", [[BTN_ONE_PRED]]);
    }

//    if (mb_strtolower($data['text']) == "подписаться")
//    {
//        $user_id = $data['peer_id'];
//        bot_sendMessageAssing($user_id);
//    }
//    else if (mb_strtolower($data['text']) == "отписаться")
//    {
//        $user_id = $data['peer_id'];
//        bot_sendMessageUnsubscribe($user_id);
//    }

    _callback_okResponse();
}

function _callback_okResponse() {
    _callback_response('ok');
}

function _callback_response($data) {
    echo $data;
    exit();
}




