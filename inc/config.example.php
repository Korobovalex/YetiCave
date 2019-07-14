<?php 

// Установка временной зоны и локали для Москвы

	date_default_timezone_set( 'Europe/Moscow' );
	setlocale(LC_ALL, 'ru_RU');

	$config = [
		'sitename' => 'Yeti Cave',
		'enable' => true
	];

	$db = [
		'host' => 'localhost',
		'user' => 'user',
		'password' => 'secret',
		'database' => 'database'
	];

?>