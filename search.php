<?php 
      //  Сенарий поиска лотов

  session_start();


  require_once 'inc/functions.php';
  require_once 'inc/db.php';

    //  Выполнение запросов в базу данных 
  $categories = db_get_categories( $link );
  $lots = db_get_all_lots( $link );


// Поиск лотов по запосу
  $search = (string) $_GET['search'] ? : '';

  if ($search) {
    $lots = db_get_lots_by_words( $link, $search );
  }


  

    // Подключение шаблонов

  $main_content = include_template( 'search.php', ['lots' => $lots,
                                                     'link' => $link
                                                  ] );


  $layout = include_template( 'layout.php', ['title' => 'Результаты поиска',
                                             'categories' => $categories,
                                             'main_content' => $main_content,
                                            ] );

  print( $layout );

?>