<?php 
      //  Основной сценарий страницы лота

  session_start();

  require_once 'inc/functions.php';
  require_once 'inc/db.php';

    //  Выполнение запросов в базу данных 
  $categories = db_get_categories( $link );
  $lots = db_get_all_lots( $link );


  if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {

    if ( isset( $_GET['lot_id'] )) {
          $lot_id = ( int ) $_GET['lot_id'];
          $lot = db_get_lot_by_id( $link, $lot_id );
          $bets = db_get_bets_by_lot( $link, $lot_id );
    
    } else {
      $lot_id = ( int ) $_REQUEST['lot_id'];
      $lot = db_get_lot_by_id( $link, $lot_id );
      $bets = db_get_bets_by_lot( $link, $lot_id );
    };
};

    // Операции со ставками

$min_bet = db_get_min_bet_by_lot( $lot );
$errors = [];

  if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    if ( empty( $_POST['bet']) || trim( $_POST['bet'] === '') ) {
      $errors[] = 'Введите сумму ставки';
    } 

    if ( !is_numeric( $_POST['bet'] )) {
      $errors[] = 'Сумма ставки должна быть числом';

    }

    if ( $_POST['bet'] < $min_bet ) {
      $errors[] = 'Ставка не может быть меньше минимальной';
    }

    if ( !count( $errors )) {
      $amount = (int) $_POST['bet'];
      $user_id = (int) $_SESSION['user']['id'];

      if ( $amount > $lot['start_price'] ) {
        update_lot_price_by_higher_bet( $link, $lot['id'], $amount );
      }

      insert_bet_db( $link, $lot_id, $user_id, $amount );
      header( "Location: lot.php?lot_id=" . $lot_id );
    }
  };

// Проврка существования лота
if ( empty( $lot )) {

    header( "Location: templates/404.php" );
    
} else {

    $main_content = include_template( 'lot-item.php', ['lot' => $lot,
                                                       'bets' => $bets,
                                                       'errors' => $errors
                                                      ] );
  };


    // Подключение шаблонов    

  $layout = include_template( 'layout.php', ['title' => $lot['title'],
                                                   'user_name' => $user_name,
                                                   'categories' => $categories,
                                                   'main_content' => $main_content,
                                                   'lot' => $lot
                                            ] );

  print( $layout );

?>