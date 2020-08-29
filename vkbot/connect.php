<?php

$mysql = mysqli_connect("localhost", "kwel_prediction", "bM5SVENm") //("хост", "имя пользователя", "пароль")
 or die("<p>Ошибка подключения к базе данных! " . mysqli_error() . "</p>");
mysqli_set_charset( $mysql, 'utf8mb4' );
 $GLOBALS["mysql"] = $mysql;
mysqli_select_db( $mysql, "kwel_prediction" )//("имя базы, с которой соединяемся")
 or die("<p>Ошибка выбора базы данных! ". mysqli_error() . "</p>");
 

?>