<?php

require_once 'createImage.php';

function bot_sendMessageOnePrediction($user_id)
{

    $sql = "SELECT `id`, `body` FROM `predictions` ORDER BY rand() LIMIT 1";

    $prediction = mysqli_query($GLOBALS["mysql"], $sql);

    $pred = mysqli_fetch_array($prediction);
    $photoURL = createImagePrediction($pred['body'], "../");

    $photo = _bot_uploadPhoto($user_id, $photoURL);

    $attachments = array(
        'photo'.$photo['owner_id'].'_'.$photo['id'],
    );

    vkApi_messagesSendAttach($user_id, $pred['body'], $attachments);

    unlink($photoURL);


    mysqli_close($GLOBALS["mysql"]);
}

function _bot_uploadPhoto($user_id, $file_name) {
    $upload_server_response = vkApi_photosGetMessagesUploadServer($user_id);
    $upload_response = vkApi_upload($upload_server_response['upload_url'], $file_name);

    $photo = $upload_response['photo'];
    $server = $upload_response['server'];
    $hash = $upload_response['hash'];

    $save_response = vkApi_photosSaveMessagesPhoto($photo, $server, $hash);
    $photo = array_pop($save_response);

    return $photo;
}
