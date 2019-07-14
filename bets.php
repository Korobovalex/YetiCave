<?php 
      //  Сценарий ставок пользователя

  session_start();

  require_once 'inc/functions.php';
  require_once 'inc/db.php';

    //  Выполнение запросов в базу данных 
  $categories = db_get_categories( $link );
  $my_bets = db_get_all_users_bets( $link, $_SESSION['user']['id'] );


    // Подключение шаблонов    

    $main_content = include_template( 'my-bets.php', ['my_bets' => $my_bets,
                                                      'link' => $link
                                                     ] );



  $layout = include_template( 'layout.php', ['user_name' => $_SESSION['user']['name'],
                                             'title' => 'Мои ставки',
                                             'categories' => $categories,
                                             'main_content' => $main_content
                                            ] );

  print( $layout );


?>