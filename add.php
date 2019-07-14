<?php 
      //  Сценарий добавления лота
  session_start();

  require_once 'inc/functions.php';
  require_once 'inc/db.php';


    //  Выполнение запросов в базу данных 
  
  $categories = db_get_categories( $link );

  // Массив полей, необходимых для заполнения
  $required_fields = ['title' => 'Не заполнено назавние',
                      'category' => 'Не выбрана категория',
                      'description' => 'Не заполнено описания',
                      'start_price' => 'Не заполнена цена',
                      'bet_step' => 'Не заполнена ставка',
                      'dt_end' => 'Не заполнена дата завершения',
                     ];

  $errors = [];

  $new_lot = [];


    // Проверка метода пепредачи данных
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
      $new_lot = $_POST;

            // Проверяется заполнение необходимых полей
      foreach ( $required_fields as $field => $error ) {
        if ( empty( $new_lot[$field] )  || trim( $new_lot[$field] ) === '' ) {
          $errors[$field] = $error;
        }
      };
              // Проверяется наличие изображения 
        if ( empty( $_FILES['image'] ) || empty( $_FILES['image']['name'] )) {
          $errors['image'] = 'Отсутствует изображение';
        }

        if ( !count( $errors )) {
            // Проверяется цена, шаг ставки, формат даты, формат изображения

          if ( $new_lot['start_price'] <= 0 || !is_numeric( $new_lot['start_price'] )) {
              $errors['start_price'] = 'Цена дожна быть числом и больше 0';
         }

          if ( $new_lot['bet_step'] <= 0 || !is_numeric( $new_lot['bet_step'] )) {
              $errors['bet_step'] = 'Ставка дожна быть числом и больше 0';
          }

          if ( !is_date_valid( $new_lot['dt_end'] )) {
            $errors['dt_end'] = 'Неправильный формат даты завершения';
          }

          $mime_type = mime_content_type( $_FILES['image']['tmp_name'] );
          
          if ($mime_type !== 'image/png' && $mime_type !== 'image/jpeg') {
                $errors['image'] = 'Требуется png или jpeg';
          }

          $filename = uniqid() . '.' . pathinfo( $_FILES['image']['name'], PATHINFO_EXTENSION );
          
          if ( empty( $errors['image']) ) {
              move_uploaded_file( $_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $filename );
          }

          if ( !count( $errors )) {
                // Аргументы для функции добавления лота в БД
                $title = $new_lot['title']; 
                $description = $new_lot['description']; 
                $image = '/uploads/' . $filename; 
                $start_price = $new_lot['start_price']; 
                $bet_step = $new_lot['bet_step']; 
                $cat_id = $new_lot['category']; 
                $author_id = $user_name; 
                $dt_end = $new_lot['dt_end'];

                  // Получение id нового лота
                $lot_id = insert_lot_db( $link, $title, $description, $image, $start_price, $bet_step, $cat_id, $author_id, $dt_end );
                  
                  // Перенаправление на страницу нового лота
                if ( !empty( $lot_id ) ) {
                  header( "Location: lot.php?lot_id=" . $lot_id );
                };
          }
        }
      }


    // Подключение шаблонов

  $main_content = include_template( 'add-lot.php', ['categories' => $categories,
                                                      'lot' => $new_lot,
                                                      'errors' => $errors,
                                                      ] );

  $layout = include_template( 'layout.php', ['title' => 'Добавление лота',
                                             'user_name' => $user_name,
                                             'categories' => $categories,
                                             'main_content' => $main_content,
                                            ] );

  print( $layout );

?>