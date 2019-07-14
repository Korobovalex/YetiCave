<?php

//  Файл подключаемых функций

	require_once( __DIR__ . '/../helpers.php');		//  Подключение дополнительных функций

	/**
	*   Форматирует цену лота для показа(отделяет тысячные нули и добавляет знак рубля)
	*
	*  	@param int $price Цена лота
	*  	@return string Отформатированная строка цены лота
	*/
		function price_formatter ( int $price ) {

		   return number_format( $price, 0, ".", " " ) . ' ₽';
		};

	/**
	*   Получает таймер времени до завершения лота
	*
	*	@param string $dt_end Дата закрытия лота в виде строки
	*  	@return string Возвращает остаток жизни лота
	*/
	function get_lot_end( string $dt_end ) {

	    $ts_end = strtotime( $dt_end );
	    $diff = $ts_end - time();
	    $hours_left = floor( $diff / 3600 );
	    $mins_left = floor(( $diff % 3600 ) / 60 );
	    $sec_left = floor($diff % 60);

	    if ( $hours_left < 10 ) {
	        $hours_left = '0' . $hours_left;
	    } 

	    if ( $mins_left < 10 ) {
	        $mins_left = '0' . $mins_left;
	    }

	    if ( $sec_left < 10 ) {
	        $sec_left = '0' . $sec_left;
	    }

	    return $hours_left . ':' . $mins_left . ':' . $sec_left;
	};

	/**
	*   Получает класс блока таймера
	*
	*	@param string $dt_end Дата закрытия лота в виде строки
	*  	@return string Возвращает класс
	*/
	function get_timer_class( string $dt_end ) {
		$timer_class = '';

		if ( get_lot_end( $dt_end ) <= '00:00:00' ) {
			return 'timer--end';
		} else if ( get_lot_end( $dt_end ) <= '01:00:00' ) {
			return 'timer--finishing';
		}
	};

	/**
	*   Получает список категорий и классов фоновых изображений
	*
	*	Создает подготовленное выражение для запроса списка имен категорий и классов фона
	*	@param $link Ресурс соединения с базой данных
	*  	@return array Возвращает массив категорий
	*/
	function db_get_categories( $link ) {
		$categories = [];
	    $sql = 'SELECT * FROM categories';
	    $stmt = mysqli_prepare( $link, $sql );
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$categories = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

		return $categories;
	};

	/**
	*   Получает список лотов
	*
	*	Создает подготовленное выражение для запроса списка актуальных лотов
	*	@param $link Ресурс соединения с базой данных
	*  	@return array Возвращает массив лотов
	*/
	function db_get_all_lots( $link ) {
		$lots = [];
		$sql = 'SELECT l.*, c.name AS category '
		. 'FROM lots l JOIN categories c ON l.cat_id = c.id WHERE l.dt_end > CURDATE()'
		. ' ORDER BY l.dt_add DESC';
	    $stmt = mysqli_prepare( $link, $sql );
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$lots = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

		return $lots;
	};

	/**
	*   Получает лот по id
	*
	*	Создает подготовленное выражение на основе id лота
	*	@param $link Ресурс соединения с базой данных
	*	@param int $lot_id id для получения деталей лота из базы данных
	*  	@return array Возвращает массив с информацией о лоте
	*/
	function db_get_lot_by_id( $link, $lot_id ) {
			$lot = [];
		$sql = "SELECT l.*, c.name AS category FROM lots l JOIN categories c ON l.cat_id = c.id WHERE l.id = ?";
	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'i', $lot_id ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$lot = mysqli_fetch_array( $result, MYSQLI_ASSOC );
		}

		return $lot;
	};
		
	/**
	*   Добавляет новый лот в базу данных
	*
	*	@param $link Ресурс соединения с базой данных
	*	@param string $title Название лота
	*	@param string $description Описание лота
	*	@param string $image Изображение лота
	*	@param string $start_price Начальная цена лота
	*	@param string $bet_step Шаг цены лота
	*	@param int $cat_id Идентификатор категории лота
	*	@param int $author_id Идентификатор автора лота
	*	@param string $dt_end Дата завершния лота
	*  	@return int Возвращает id нового лота в БД
	*/
    function insert_lot_db( $link, $title, $description, $image, $start_price, $bet_step, $cat_id, $author_id, $dt_end ) {
      $sql = 'INSERT INTO lots (title, description, image, start_price, bet_step, cat_id, author_id, dt_end) VALUES(?, ?, ?, ?, ?, ?, ?, ?)';

      $stmt = mysqli_prepare( $link, $sql );
      mysqli_stmt_bind_param( $stmt, 'sssssiis', $title, $description, $image, $start_price, $bet_step, $cat_id, $author_id, $dt_end );
      $result = mysqli_stmt_execute( $stmt );

      if ( $result ) {
        $lot_id = mysqli_insert_id( $link );
      }
      
      return $lot_id; 
  	};

	/**
	*   Проверяет наличие в БД пользователя с переданным email
	*
	*	@param $link Ресурс соединения с базой данных
	*	@param string $email Электронная почта пользователя
	* 	@return bool Возвращает true, если email используется и false, если нет
	*/
	function db_check_exist_email( $link, $email ) {
	   	$user = [];
		$sql = "SELECT * FROM users WHERE email = ?";
	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 's', $email ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$user = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

		return empty( $user ) ? false : true;
	};

  	/**
	*   Дбавляет нового пользователя в БД
	*
	*	@param $link Ресурс соединения с БД
	*	@param string $email электронная почта
	*	@param string $name Имя пользователя
	*	@param string $password Пароль
	*	@param string $contacts Контакты
	*  	@return int Возвращает id нового пользователя в БД
	*/
    function insert_user_db( $link, $email, $name, $password, $contacts ) {
      $sql = 'INSERT INTO users (email, name, password, contacts) VALUES(?, ?, ?, ?)';

      $stmt = mysqli_prepare( $link, $sql );
      mysqli_stmt_bind_param( $stmt, 'ssss', $email, $name, $password, $contacts );
      $result = mysqli_stmt_execute( $stmt );

      if ( $result ) {
        $user_id = mysqli_insert_id( $link );
      }
      
      return $user_id; 
  	};

  	/**
	*   Получает данные о пользователе по  его email
	*
	*	@param $link Ресурс соединения с базой данных
	*	@param string $email електронная почта пользователя
	*  	@return array Возвращает массив с данными пользователя
	*/
	function db_get_user_by_email( $link, $email ) {
	   	$user = [];
		$sql = "SELECT * FROM users WHERE email = ?";
	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 's', $email ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$user = mysqli_fetch_array( $result, MYSQLI_ASSOC );
		}

		return $user;
	};

	/**
	*   Получает массив ставок для лота по его id
	*
	*	@param $link Ресурс соединения с базой данных
	*	@param string $lot_id id лота
	*  	@return array $bets Возвращает массив ставок для определенного лота
	*/
	function db_get_bets_by_lot( $link, $lot_id ) {
		$bets = [];
		$sql = "SELECT b.*, l.title AS lot, (SELECT name FROM users u WHERE u.id = b.user_id) AS user FROM	bets b INNER JOIN lots l ON b.lot_id = l.id && l.id = ? ORDER BY b.dt_add DESC";
	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'i', $lot_id ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$bets = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

		return $bets;
	};

	/**
	*   Получает массив ставок для пользователя по его id
	*
	*	@param $link Ресурс соединения с базой данных
	*	@param string $user_id id пользователя
	*  	@return array $bets Возвращает массив ставок для определенного лота
	*/
	function db_get_all_users_bets( $link, $user_id ) {
		$my_bets = [];
		$sql = "SELECT b.id AS bet_id, b.amount, b.dt_add AS bet_dt, l.id AS lot_id, l.title, l.cat_id, l.start_price, l.image AS image, l.dt_end AS lot_end, c.name AS cat_name, u.id as user_id FROM lots l JOIN bets b ON b.lot_id = l.id JOIN categories c ON l.cat_id = c.id JOIN users u ON b.user_id = u.id WHERE u.id = ?";
		$stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'i', $user_id ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$my_bets = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

		return $my_bets;
	};

	/**
	*   Получает массив ставок пользователя по его id
	*
	*	@param $link Ресурс соединения с базой данных
	*	@param string $user_id id пользователя
	*  	@return array $bets Возвращает массив ставок определенного пользователя
	*/
	function db_get_bets_by_user( $link, $user_id ) {
		$bets = [];
		$sql = "SELECT b.*, u.id, (SELECT name FROM users u WHERE u.id = b.user_id) AS user FROM bets b INNER JOIN users u ON b.user_id = u.id && u.id = ?";
	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'i', $user_id ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$bets = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

		return $bets;
	};

	/**
	*   Получает минимальную ставку для лота
	*
	*	@param array $lot Массив данных лота
	*  	@return $min_bet Возвращает сумму минимальной ставки по лоту(сумму стартовой цены и шага ставки) в отформатированном виде
	*/
	function db_get_min_bet_by_lot( $lot ) {
		$min_bet = $lot['start_price'] + $lot['bet_step'];

		return number_format( $min_bet, 0, ".", " " ) . ' р';
	};
	
	/**
	*   Получает максимальную ставку для лота по его id
	*
	*	@param $link Ресурс соединения с базой данных
	*	@param string $lot_id id лота
	*  	@return array $max_bet Возвращает массив с последней ставкой
	*/
	function db_get_max_bet_by_lot( $link, $lot_id ) {
		$sql = "SELECT MAX(amount) AS maxbet FROM bets WHERE lot_id = ?";
	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'i', $lot_id ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$max_bet = mysqli_fetch_row( $result );
		}

		return $max_bet;
	};
	
	/**
	*   Форматирует дату ставки
	*
	*	@param $date Дата создания ставки
	*  	@return string $date_time Возвращает отформатированную дату ставки в формате "ГГ.ММ.ДД в ЧЧ:ММ"
	*/
	function bet_dt_formatter( $date ) {
		$ts = strtotime( $date );
		$date = date("d.m.y", $ts);
		$time = date("h:i", $ts);
		$date_time = $date . ' в ' . $time;

		return $date_time;
	};

	/**
	*   Форматирует дату завершения лота
	*
	*	@param $date Дата завершения лота
	*  	@return string $date_time Возвращает отформатированную дату завершения лота в формате "ГГ.ММ.ДД в ЧЧ:ММ"
	*/
	function lot_end_formatter( $date ) {
		$ts = strtotime( $date );
		$date = date("d.m.y", $ts);
		$time = date("h:i", $ts);
		$date_time = $date . ' в ' . $time;

		return $date_time;
	};

	/**
	*   Дбавляет новую ставку в БД
	*
	*	@param $link Ресурс соединения с БД
	*	@param int $lot_id id лота ставки
	*	@param int $user_id id автора ставки
	*	@param int $amount Сумма ставки
	*	@param string $contacts Контакты
	*  	@return int Возвращает id новой ставки в БД 
	*/
    function insert_bet_db( $link, $lot_id, $user_id, $amount ) {
      	$sql = "INSERT INTO bets (lot_id, user_id, amount) VALUES(?, ?, ?)";

      	$stmt = mysqli_prepare( $link, $sql );
      	mysqli_stmt_bind_param( $stmt, 'iii', $lot_id, $user_id, $amount );
      	$result = mysqli_stmt_execute( $stmt );
  	};

	/**
	*   Форматирует сумму ставки для показа(отделяет тысячные нули и добавляет знак рубля)
	*
	*  	@param int $bet Цена лота
	*  	@return string Отформатированная строка цены лота
	*/
	function bet_amount_formatter( $bet ) {
		return number_format( $bet, 0, ".", " " ) . ' р';
	};

	/**
	*   Ищет лоты по ключевым словам из запроса
	*
	*		Создает подготовленное выражение на основе поисковых слов
	*  	@param $link Ресурс соединения с базой данных
	*		@param string $search поисковый запрос из формы
	*  	@return array Возвращает массив найденных лотов с данными для показа на странице
	**/
	function db_get_lots_by_words( $link, string $search ) {
	  	$lots = [];
	  	$sql = "SELECT l.id, l.title, l.image, l.start_price, l.dt_end, c.name AS category FROM lots l JOIN categories c ON l.cat_id = c.id WHERE MATCH(title, description) AGAINST(?) && l.dt_end > CURDATE() ORDER BY l.dt_add DESC";
    	$stmt = mysqli_prepare( $link, $sql );
    	mysqli_stmt_bind_param( $stmt, 's', $search ); 
    	mysqli_stmt_execute( $stmt );
    	$result = mysqli_stmt_get_result( $stmt );

    	if ( $result ) {
      		$lots = mysqli_fetch_all( $result, MYSQLI_ASSOC );
    	}

    	return $lots;
	};

	/**
	*   Получает общее количество лотов в БД
	*
	*		@param $link Ресурс соединения с базой данных
  	*		@return int Возвращает число лотов
	*/
	function count_all_lots( $link ) {

	  	$result = mysqli_query( $link, 'SELECT COUNT(*) AS count FROM lots' );

	  	if ( $result ) {
	  		return mysqli_fetch_assoc( $result )['count'];
	  	}
	};

	/**
	*   Получает массив лотов из БД для показа в постраничном режиме
	*
	*		@param $link Ресурс соединения с базой данных
	*		@param int $limit Ограничение количества показываемых на странице лотов
	*		@param int $offset смещение выборки
  	*		@return array Возвращает массив лотов
	*/
	function get_lots_by_ofset( $link, $limit, $offset ) {
	  	$lots = [];

	  	$sql = "SELECT l.id, l.title, l.image, l.start_price, l.dt_end, c.name AS category FROM lots l JOIN categories c ON l.cat_id = c.id WHERE l.dt_end > CURDATE() ORDER BY l.dt_add DESC LIMIT " . $limit . " OFFSET " . $offset;
	  	$stmt = mysqli_prepare( $link, $sql );
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$lots = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

	  	return $lots;
	};

	/**
	*   Получает массив лотов из БД по id категории
	*
	*		@param $link Ресурс соединения с базой данных
	*		@param int $limit Ограничение количества показываемых на странице лотов
	*		@param int $offset смещение выборки
  	*		@return array Возвращает массив лотов
	*/
	function db_get_lots_by_cat( $link, $cat_id ) {
	  	$lots = [];
	  	$sql = "SELECT l.*, c.name AS category FROM lots l JOIN categories c ON l.cat_id = c.id && c.id =" . $cat_id . " WHERE l.dt_end > CURDATE() ORDER BY l.dt_add DESC";
	  	$stmt = mysqli_prepare( $link, $sql );
	  	mysqli_stmt_execute( $stmt );
	  	$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$lots = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

	  	return $lots;
	};

	/**
	*   Получает массив завершенных лотов из БД
	*
	*		@param $link Ресурс соединения с базой данных
  	*		@return array Возвращает массив лотов с истекшей датой завершения
	*/
	function db_get_expired_lots_without_winner( $link ) {
	  	$lots = [];
	  	$sql = "SELECT id as lot_id FROM lots WHERE winner_id IS NULL && dt_end <= CURDATE()";

	  	$stmt = mysqli_prepare( $link, $sql );
	  	mysqli_stmt_execute( $stmt );
	  	$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$lots = mysqli_fetch_all( $result, MYSQLI_ASSOC );
		}

	  	return $lots;
	};

	/**
	*   Добавляет в БД победителя торгов по id лота
	*
	*		@param $link Ресурс соединения с базой данных
	*		@param int $lot_id Id лота
	*		@param int $winner_id Id пользователя-победителя
	*/
	function set_winner_db_by_lot( $link, $lot_id, $winner_id ) {
	  	$sql = "UPDATE lots	SET winner_id = ? WHERE id = ?";

	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'ii', $winner_id, $lot_id );
	    $result = mysqli_stmt_execute( $stmt );
	};

	/**
	*   Получает самую свежую ставку для лота из БД по его id 
	*
	*		@param $link Ресурс соединения с базой данных
	*		@param int $lot_id Id лота
  	*		@return array $last_bet Возвращает последнюю ставку
	*/
	function db_get_last_bet_by_lot( $link, $lot_id ) {
		$sql = "SELECT * FROM bets WHERE dt_add = (select MAX(dt_add) from bets WHERE lot_id = ?) && lot_id = ?";
	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'ii', $lot_id, $lot_id ); 
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
			$last_bet = mysqli_fetch_array( $result, MYSQLI_ASSOC );
		}

		return $last_bet;	  	
	};

    /**
    *   Получает имя и почту побелителя торгов по id лота
    *
    *	@param $link Ресурс соединения с базой данных
    *	@param $lot_id Id лота
	    *	@return array Возвращает массив c данными победителя
    */
  	function db_get_winner_by_lot( $link, $lot_id ) {
  		$user = [];
  		$sql = "SELECT u.id, u.name as username, u.email as usermail FROM bets b JOIN users u ON b.user_id = u.id WHERE b.dt_add = (select MAX(dt_add) from bets WHERE lot_id = " . $lot_id . " ) && lot_id = " . $lot_id;

	    $stmt = mysqli_prepare( $link, $sql );
		mysqli_stmt_execute( $stmt );
		$result = mysqli_stmt_get_result( $stmt );

		if ( $result ) {
	        $user =  mysqli_fetch_array( $result, MYSQLI_ASSOC );
	    }
      
      	return $user; 
  	};

    /**
    *   Проверяет последняя ли ставка в торгах
    *
    *	@param $link Ресурс соединения с базой данных
    *	@param array $bet Массив данных по ставке
    *	@param int $lot_id Id лота
	*	@return bool true, если ставка последняя и false, если нет
    */
  	function is_last_bet( $link, $bet, $lot_id ) {
  		$last_bet = db_get_last_bet_by_lot( $link, $lot_id );

  		return ( $last_bet['id'] === $bet['bet_id'] ) ? true : false;
  	};

  	/**
    *   Обновляет цену лота, если ставка больше начальной
    *
    *	@param $link Ресурс соединения с базой данных
    *	@param int $lot_id Id лота
    *	@param int $start_price Начальная цена лота
    */
    function update_lot_price_by_higher_bet( $link, $lot_id, $amount ) {
	    $sql = "UPDATE lots	SET start_price = ? WHERE id = ?";

	    $stmt = mysqli_prepare( $link, $sql );
	    mysqli_stmt_bind_param( $stmt, 'ii', $amount, $lot_id );
	    $result = mysqli_stmt_execute( $stmt );
    };

  	/**
    *   Получает класс блока ставки пользователя
    *
    *	@param $link Ресурс соединения с базой данных
    *	@param array $bet Массив с данными по ставке
    *	@param int $lot_id Id лота
   	*	@return string Класс для блока ставки
    */
    function get_rates_item_class( $link, $bet, $lot_id ) {
    	$winner = db_get_winner_by_lot( $link, $lot_id );
    	if ( $winner['id'] === $bet['user_id'] ) {
    		return 'rates__item--win';
    	} else if ( get_timer_class($bet['lot_end'] === 'timer--end') ) {
    		return 'rates__item--end';
		} else {
    		return '';
    	}
    };

  	/**
    *   Получает класс блока таймера ставки
    *
    *	@param $link Ресурс соединения с базой данных
    *	@param array $bet Массив с данными по ставке
    *	@param int $lot_id Id лота
   	*	@return string Класс для блока таймера
    */
    function get_rates_timer_class( $link, $bet, $lot_id ) {
    	$winner = db_get_winner_by_lot( $link, $lot_id );
    	if ( $winner['id'] === $bet['user_id'] ) {
    		return 'timer--win';
    	} else if ( get_timer_class($bet['lot_end'] === 'timer--end') ) {
    		return 'timer--end';
    	} else if ( get_timer_class($bet['lot_end'] === 'timer--finishing') ) {
    		return 'timer--finishing';
    	} else {
    		return '';
    	}
    };

  	/**
    *   Получает статус ставки
    *
    *	@param $link Ресурс соединения с базой данных
    *	@param array $bet Массив с данными по ставке
    *	@param int $lot_id Id лота
   	*	@return string Статус ставки или таймер, если торги не заквершены
    */
    function get_rates_status( $link, $bet, $lot_id ) {
    	$winner = db_get_winner_by_lot( $link, $lot_id );
    	if ( $winner['id'] === $bet['user_id'] ) {
    		return 'Ставка выиграла';
    	} else if ( get_timer_class($bet['lot_end'] === 'timer--end') ) {
    		return 'Торги окончены';
    	} else {
    		return get_lot_end($bet['lot_end']);
    	}
    };


?>