<?php

$mysql = mysqli_connect("localhost", "kwel_prediction", "bM5SVENm") //("����", "��� ������������", "������")
 or die("<p>������ ����������� � ���� ������! " . mysqli_error() . "</p>");
mysqli_set_charset( $mysql, 'utf8mb4' );
 $GLOBALS["mysql"] = $mysql;
mysqli_select_db( $mysql, "kwel_prediction" )//("��� ����, � ������� �����������")
 or die("<p>������ ������ ���� ������! ". mysqli_error() . "</p>");
 

?>