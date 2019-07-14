<?php 

//   Основной сценарий страницы

	// Открытие сеанса, идентификация пользователя
	session_start();
	
	// Подключение функций и БД
	require_once 'inc/functions.php';
	require_once 'inc/db.php';
	require_once 'vendor/autoload.php';
	require_once 'getwinner.php';

// Идентификация пользователя

	$user = $_SESSION['user'];


    //  Выполнение запросов в базу данных	
	
	$categories = db_get_categories( $link );

	$lots = db_get_all_lots( $link );

	// Пересчет стартовой цены лотов по последним(максимальным ставкам)
	foreach( $lots as $lot ) {
		$max_bet = db_get_max_bet_by_lot( $link, $lot['id'] );
		update_lot_price_by_higher_bet( $link, $lot['id'], $max_bet[0] );
	}


// Фильтр по категориям
  $cat_id = (int) $_GET['cat_id'] ? : 0;

  if ( !empty( $cat_id ) && $cat_id !== 0 ) {
    $lots = db_get_lots_by_cat( $link, $cat_id );
  } else {
	// Пагинация
	$lots_count = count_all_lots( $link );
	$page_lots = 3; // количество лотов на странице
	$curr_page = empty( $_GET['page'] ) ? 1 : intval( $_GET['page'] ); // текущая страница из запроса или 1-
	$pages_count = ceil( $lots_count / $page_lots ); // общее число страниц
	$offset = ( $curr_page - 1 ) * $page_lots; // смещение

	$pages = range( 1, $pages_count);
	
	$lots = get_lots_by_ofset( $link, $page_lots, $offset );
  }


    // Подключение шаблонов

	if ($config['enable']) {

		$pager = include_template( 'pager.php', ['pages_count' => $pages_count,
    										  			'pages' => $pages,
    										  			'curr_page' => $curr_page
												] );

		$main_content = include_template( 'main.php', ['categories' => $categories, 
												       'lots' => $lots,
												       'pager' => $pager
												  	  ] );

	} else {
		$err = 'Сайт временно недоступен. Ведутся технические работы.';
		$main_content = include_template( 'error.php', ['err' => $err
			  										   ] );
	}

	$layout = include_template( 'layout.php', ['user_name' => $user,
	                                           'main_content' => $main_content,
	                                           'categories' => $categories,
	                                           'categories' => $categories,
	                                           'title' => 'Главная'
	                                          ] );

	print( $layout );

?>