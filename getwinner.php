<?php 
	// Сценарий определения и поздравления победителей торгов
	session_start();

	require_once 'vendor/autoload.php';
	require_once 'vendor/autoload.php';
	require_once 'inc/functions.php';

	// Конфигурация для отправки сообщений
	$transport = new Swift_SmtpTransport( "phpdemo.ru", 25 );
	$transport->setUsername( "keks@phpdemo.ru" );
	$transport->setPassword( "htmlacademy" );
	$mailer = new Swift_Mailer( $transport ); 

	// Отправка сообщений победителям
	$expired_lots = db_get_expired_lots_without_winner( $link);

	foreach ( $expired_lots as $expired_lot ) {

		$winner = db_get_winner_by_lot( $link, $expired_lot['lot_id'] );
		$username = $winner['username'];
		$lot = db_get_lot_by_id( $link, $expired_lot['lot_id'] );

		set_winner_db_by_lot( $link, $lot['id'], $winner['id'] );
		
		$message = new Swift_Message(); 
		$message->setSubject( "Ваша ставка победила" ); 
		$message->setFrom( ["keks@phpdemo.ru" => "YetiCave"] ); 
		$message->setTo( $winner['usermail'] );

		$message_content = include_template('mail.php', ['lot' => $lot,
														 'username' => $username
														] );
    

    	$message->setBody( $message_content, 'text/html' );

		$result = $mailer->send( $message ); 
}
