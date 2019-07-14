<!-- Шаблон детальной информации о лоте -->
    <section class="lot-item container">
      <h2><?= htmlspecialchars( $lot['title'] ); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?= $lot['image']; ?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?= htmlspecialchars( $lot['category'] ); ?></span></p>
          <p class="lot-item__description"><?= htmlspecialchars($lot['description'] ); ?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <div class="lot-item__timer lot__timer <?= get_timer_class( $lot['dt_end'] ); ?>">
              <?= get_lot_end( $lot['dt_end'] ); ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?= price_formatter( $lot['start_price'] ); ?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?= db_get_min_bet_by_lot( $lot ); ?></span>
              </div>
            </div>
            <form class="lot-item__form" action="" method="post" enctype="application/x-www-form-
urlencoded" autocomplete="off">
              <p class="lot-item__form-item form__item <?= !empty( $error ) ? 'form_item--invalid' : ''; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="bet" placeholder="<?= db_get_min_bet_by_lot( $lot ); ?>">
                <span class="form__error"></span>
                      <ul class="form__error">
                      <?php foreach ( $errors as $key => $error ) :?>
                        <li class="form__error--item"><?= $error ?></li>
                      <?php endforeach; ?>
                      </ul>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <div class="history">
            <h3>История ставок (<span><?= count( $bets ); ?></span>)</h3>
            <table class="history__list">
              <?php foreach ( $bets as $bet ): ?>
                <tr class="history__item">
                  <td class="history__name"><?= $bet['user'] ?></td>
                  <td class="history__price"><?= bet_amount_formatter( $bet['amount']) ?></td>
                  <td class="history__time"><?= bet_dt_formatter( $bet['dt_add']) ?></td>
                </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>
      </div>
    </section>