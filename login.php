<?php 
      //  Сценарий входа на сайт
  session_start();

  require_once 'inc/functions.php';
  require_once 'inc/db.php';
  require_once 'vendor/autoload.php';



      //  Выполнение запросов в базу данных 
  
  $categories = db_get_categories( $link );

      // Идентификация пользователя

    // Массив полей, необходимых для заполнения
  $required_fields = ['email' => 'Не указана электронная почта.',
                      'password' => 'Не введен пароль.',
                     ];

  $errors = [];
  $account = [];
  $user = [];


  	// Проверка метода передачи данных
  	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        // Проверка заполнения необходимых полей
	    foreach ( $required_fields as $field => $error ) {
	        if ( empty( $_POST[$field] ) || trim( $_POST[$field] ) === '' ) {
	          $errors[$field] = $error;
	        }
	    };

      if ( !count( $errors )) {

        $account = $_POST;
        $pass_hash = password_hash( $account['password'], PASSWORD_DEFAULT );
              
        // Получение данных пользователя из БД
        $user = db_get_user_by_email( $link, $account['email'] );

        // Проверка получения данных пользователя
        if ( empty( $user )) {
          $errors['email'] = 'Пользователя с таким e-mail нет в базе.';          
        } 
              
        // Проверка соответствия введенного пароля
        
        if ( !password_verify( (string) $user['password'], (string) $pass_hash )) {
      		$errors['email'] = 'Пароль не соответствует введенному e-mail.';
      	}

        if ( !count( $errors )) {

            $_SESSION['username'] = $user['name'];
            $_SESSION['user'] = $user;
              
              // Перенаправление на главную страницу
            header( "Location: index.php" );
        }
      }
    };

    // Подключение шаблонов
  $main_content = include_template( 'login-form.php', ['categories' => $categories,
                                                      'user' => $user,
                                                      'errors' => $errors,
                                                      ] );

  $layout = include_template( 'layout.php', ['title' => 'Вход',
                                             'user' => $user,
                                             'categories' => $categories,
                                             'main_content' => $main_content,
                                            ] );

  print( $layout );

?>