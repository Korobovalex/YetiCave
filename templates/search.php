<div class="container">
  <section class="lots">
    <h2>Результаты поиска по запросу «<span><?= htmlspecialchars( $_GET['search'] ); ?></span>»</h2>
    <?php if ( empty( $lots ) ) : ?>
      <h3>По вашему запросу ничего н найдено.</h3>
    <?php else : ?>
      <ul class="lots__list">
        <?php foreach ($lots as $lot) : ?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?=  htmlspecialchars( $lot['image'] ); ?>" width="350" height="260" alt="Сноуборд">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?= htmlspecialchars( $lot['category'] ); ?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?= $lot['id']; ?>"><?= htmlspecialchars( $lot['title'] )?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
                <span class="lot__amount"><?= count(db_get_bets_by_lot( $link, $lot['id'] )) . get_noun_plural_form( count(db_get_bets_by_lot( $link, $lot['id'] )), ' ставка', ' ставки', ' ставок' ); ?></span>
                <span class="lot__cost"><?= price_formatter( $lot['start_price'] ); ?></span>
              </div>
              <div class="lot__timer <?= get_timer_class( $lot['dt_end'] ); ?>">
                <?= get_lot_end( $lot['dt_end'] ); ?>
              </div>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</div>

