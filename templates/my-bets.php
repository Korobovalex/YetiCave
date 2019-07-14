    <!-- Шаблон ставок пользователя -->
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ( $my_bets as $my_bet ): ?>
        <tr class="rates__item <?= get_rates_item_class( $link, $my_bet, $my_bet['lot_id'] ); ?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?= $my_bet['image']; ?>" width="54" height="40" alt="Сноуборд">
            </div>
            <h3 class="rates__title"><a href="lot.php?lot_id=<?= $my_bet['lot_id']; ?>"><?= $my_bet['title']; ?></a></h3>
          </td>
          <td class="rates__category">
            <?= $my_bet['cat_name']; ?>
          </td>
          <td class="rates__timer">
            <div class="timer <?= get_rates_timer_class( $link, $my_bet, $my_bet['lot_id'] ); ?>"><?= get_rates_status( $link, $my_bet, $my_bet['lot_id'] ); ?></div>
          </td>
          <td class="rates__price">
            <?= bet_amount_formatter( db_get_max_bet_by_lot( $link, $my_bet['lot_id'] )[0] ); ?>
          </td>
          <td class="rates__time">
            <?= bet_dt_formatter( $my_bet['bet_dt'] ); ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </section>
  