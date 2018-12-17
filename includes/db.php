<?php

require_once 'config.php';

$link = mysqli_connect( DB_HOST, DB_USER, DB_PASS );

if ( false === $link )
    die ('Помилка з\'єднання із базою даних');

$db_selected = mysqli_select_db( $link, DB_BASE );

if ( false === $link )
    die ( 'Помилка з\'єднання із базою даних' );

mysqli_query( $link, "SET collation_connection = utf8_general_ci" );
mysqli_query( $link, "SET NAMES utf8" );
