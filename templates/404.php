<?php 
    header("HTTP/ 1.1 404 Not found");
    require_once( __DIR__ . '/../inc/db.php');
    $categories = db_get_categories( $link );
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ошибка 404</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <div class="page-wrapper">
          <header class="main-header">
    <div class="main-header__container container">
      <h1 class="visually-hidden">YetiCave</h1>
      <a class="main-header__logo" href="index.html">
        <img src="../img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
      </a>
      <form class="main-header__search" method="get" action="https://echo.htmlacademy.ru" autocomplete="off">
        <input type="search" name="search" placeholder="Поиск лота">
        <input class="main-header__search-btn" type="submit" name="find" value="Найти">
      </form>
      <a class="main-header__add-lot button" href="../add.php">Добавить лот</a>
      <nav class="user-menu">
          <?php if ( $is_auth === 1 ): ?>
              <div class="user-menu__logged">
                  <p><?= htmlspecialchars( $user_name ); ?></p>
                  <a class="user-menu__bets" href="pages/my-bets.html">Мои ставки</a>
                  <a class="user-menu__logout" href="#">Выход</a>
              </div>
          <?php else: ?>
              <ul class="user-menu__list">
                  <li class="user-menu__item">
                    <a href="#">Регистрация</a>
                  </li>
                  <li class="user-menu__item">
                    <a href="#">Вход</a>
                  </li>
              </ul>
          <?php endif; ?>
      </nav>
    </div>
  </header>

        <main>
          <nav class="nav">
            <ul class="nav__list container">
            <?php foreach ( $categories as $category ): ?>
              <li class="nav__item">
                <a href="pages/all-lots.html"><?=htmlspecialchars( $category['name'] ); ?></a>
              </li>
            <?php endforeach; ?>
            </ul>
    </nav>
          <section class="lot-item container">
              <h2>Страница не найдена</h2>
              <p>Данной страницы не существует на сайте.</p>
          </section>
        </main>
        
    </div>
</body>
</html>