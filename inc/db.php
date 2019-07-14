<?php 

	// Файл Инициализации базы данных
    require_once 'functions.php';

    if ( !file_exists( __DIR__ . '/config.php' )) {
        echo 'Отсутствует файл настроек базы данных.';
        exit;
    }

    $db = [];
    
    require_once 'config.php';

    // Подключение к базе данных

	$link = mysqli_connect( $db['host'], $db['user'], $db['password'], $db['database'] ) or die( mysqli_connect_error() );

	mysqli_set_charset( $link, "utf8" );

?>