<?php


function bot_userIsAssign($user_id)
{
    $users_get_response = vkApi_usersGet($user_id);
//    $user = array_pop($users_get_response);

    $result = mysqli_query( $GLOBALS["mysql"], "SELECT `id` FROM `users` WHERE ( `user_id` = '{$user_id}' )" );
    return mysqli_num_rows( $result ) ? true : false;
}

function bot_sendMessageAssing($user_id) {
    $users_get_response = vkApi_usersGet($user_id);
    $user = array_pop($users_get_response);

    $result = mysqli_query( $GLOBALS["mysql"], "SELECT `id` FROM `users` WHERE ( `user_id` = '{$user['id']}' )" );
    if (mysqli_num_rows( $result ))
    {
        $msg = "Вы уже подписаны";
    }
    else {
        $sql = "INSERT INTO `users` (`user_id`) VALUES ('{$user['id']}')";
        if (mysqli_query($GLOBALS["mysql"], $sql)) {
            $msg = "Вы подписались на предсказания";
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($GLOBALS["mysql"]);
        }
    }

    vkApi_messagesSend($user_id, $msg);
//    return mysqli_num_rows( $result ) ? true : false;
}

function bot_sendMessageUnsubscribe($user_id) {
    $users_get_response = vkApi_usersGet($user_id);
    $user = array_pop($users_get_response);

    $result = mysqli_query( $GLOBALS["mysql"], "SELECT `id` FROM `users` WHERE ( `user_id` = '{$user['id']}')" );
    if (!mysqli_num_rows( $result ))
    {
        $msg = "Вы еще не подписаны на рассылку";
    }
    else {
        $sql = "DELETE FROM users WHERE user_id= '{$user['id']}'";
        if (mysqli_query($GLOBALS["mysql"], $sql)) {
            $msg = "Вы отписались от предсказаний";
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($GLOBALS["mysql"]);
        }
    }
    vkApi_messagesSend($user_id, $msg);

}



