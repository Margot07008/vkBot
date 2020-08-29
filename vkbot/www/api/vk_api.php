<?php

define('VK_API_VERSION', '5.50'); //Используемая версия API
define('VK_API_ENDPOINT', 'https://api.vk.com/method/');


function vkApi_messagesSend($peer_id, $message) {
    return _vkApi_call('messages.send', array(
        'peer_id'    => $peer_id,
        'message'    => $message,
    ));
}

function vkApi_messagesSendAttach($peer_id, $message, $attachments = array()) {
    return _vkApi_call('messages.send', array(
        'peer_id'    => $peer_id,
        'message'    => $message,
        'attachment' => implode(',', $attachments)
    ));
}

function vkApi_usersGet($user_id) {
    return _vkApi_call('users.get', array(
        'user_id' => $user_id,
    ));
}

function vkApi_photosGetMessagesUploadServer($peer_id) {
    return _vkApi_call('photos.getMessagesUploadServer', array(
        'peer_id' => $peer_id,
    ));
}

function vkApi_photosSaveMessagesPhoto($photo, $server, $hash) {
    return _vkApi_call('photos.saveMessagesPhoto', array(
        'photo'  => $photo,
        'server' => $server,
        'hash'   => $hash,
    ));
}


function _vkApi_call($method, $params = array()) {
    $params['access_token'] = VK_API_ACCESS_TOKEN;
    $params['v'] = VK_API_VERSION;

    $query = http_build_query($params);
    $url = VK_API_ENDPOINT.$method.'?'.$query;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($json, true);

    return $response['response'];
}


function vkApi_upload($url, $file_name) {
    if (!file_exists($file_name)) {
        throw new Exception('File not found: '.$file_name);
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CURLfile($file_name)));
    $json = curl_exec($curl);
   
    curl_close($curl);

    $response = json_decode($json, true);
    if (!$response) {
        throw new Exception("Invalid response for {$url} request");
    }

    return $response;
}