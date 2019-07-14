<?php 
      //  Сценарий добавления аккаунта

  session_start();

  require_once 'inc/functions.php';
  require_once 'inc/db.php';
  require_once 'vendor/autoload.php';


      //  Выполнение запросов в базу данных 
  
  $categories = db_get_categories( $link ); 

    // Массив полей, необходимых для заполнения
  $required_fields = ['email' => 'Не указана электронная почта',
                      'password' => 'Не введен пароль',
                      'name' => 'Не указано имя',
                      'contacts' => 'Не заполнены контактные данные',
                     ];

  $errors = [];

  $new_user = [];

  	// Проверка метода передачи данных
  	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	    $new_user = $_POST;
        
        // Проверка заполнения необходимых полей
	    foreach ( $required_fields as $field => $error ) {
	        if ( empty( $new_user[$field] )  || trim( $new_user[$field] ) === '' ) {
	          $errors[$field] = $error;
	        }
	    };

        if ( !count( $errors )) {
        	if ( !filter_var( $new_user['email'], FILTER_VALIDATE_EMAIL )) {
        		$errors['email'] = 'Введен невалидный адрес почты';
        	}

        	if ( db_check_exist_email( $link, $new_user['email'] )) {
        	$errors['email'] = 'email уже используется';	
        	}

        	if ( !count( $errors )) {
              	$email = (string) $new_user['email'];
              	$name = (string) $new_user['name'];
              	$pass_hash = password_hash( $new_user['password'], PASSWORD_DEFAULT );
              	$contacts = (string) $new_user['contacts'];
              	
              	// Добавление нового пользователя в БД, получение его id
              	$user_id = insert_user_db( $link, $email, $name, $pass_hash, $contacts );

              // Проверка наличия id, перенаправление на страницу ...
	            if ( !empty( $user_id )) {
	              header( "Location: login.php" );
	            }
        	}
        }
	};

    // Подключение шаблонов
  $main_content = include_template( 'add-user.php', ['categories' => $categories,
                                                     'user' => $new_user,
                                                     'errors' => $errors,
                                                    ] );

  $layout = include_template( 'layout.php', ['title' => 'Регистрация',
                                             'user_name' => $user_name,
                                             'categories' => $categories,
                                             'main_content' => $main_content,
                                            ] );

  print( $layout );

?>