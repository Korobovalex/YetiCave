        <!-- Форма регистрации нового аккаунта -->
    <form class="form container <?= !empty( $errors ) ? 'form--invalid' : ''; ?>" action="sign-up.php" method="POST" enctype="application/x-www-form-
urlencoded" autocomplete="off">
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?= !empty( $errors['email'] ) ? 'form_item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $user['email'] ) && (string) $user['email'] ) ? $user['email'] : ''; ?>">
        <span class="form__error">Введите e-mail</span>
      </div>
      <div class="form__item <?= !empty( $errors['password'] ) ? 'form_item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $user['password'] ) && (string) $user['password'] ) ? $user['password'] : ''; ?>">
        <span class="form__error">Введите пароль</span>
      </div>
      <div class="form__item <?= !empty( $errors['name'] ) ? 'form_item--invalid' : ''; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $user['name'] ) && (string) $user['name'] ) ? $user['name'] : ''; ?>">
        <span class="form__error">Введите имя</span>
      </div>
      <div class="form__item <?= !empty( $errors['contacts'] ) ? 'form_item--invalid' : ''; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $user['contacts'] ) && (string) $user['contacts'] ) ? $user['contacts'] : ''; ?>"></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <ul>
      <?php foreach ($errors as $key => $error) :?>
        <li class="form__error--item"><?= $error ?></li>
      <?php endforeach; ?>
      </ul>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>