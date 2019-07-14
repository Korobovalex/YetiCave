    <!-- Форма входа -->
    <form class="form container <?= !empty( $errors ) ? 'form--invalid' : ''; ?>" action="login.php" method="post" enctype="application/x-www-form-
urlencoded">
      <h2>Вход</h2>
      <div class="form__item <?= !empty( $errors['email'] ) ? 'form_item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $user['email'] ) && (string) $user['email'] ) ? $user['email'] : ''; ?>">
        <span class="form__error">Введите e-mail</span>
      </div>
      <div class="form__item <?= !empty( $errors['password'] ) ? 'form_item--invalid' : ''; ?> form__item--last">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $user['password'] ) && (string) $user['password'] ) ? $user['password'] : ''; ?>">
        <span class="form__error">Введите пароль</span>
      </div>
      <ul>
      <?php foreach ($errors as $key => $error) :?>
        <li class="form__error--item"><?= $error ?></li>
      <?php endforeach; ?>
      </ul>
      <button type="submit" class="button">Войти</button>
    </form>