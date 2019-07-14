<!-- Главное содержимое странииы -->
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
    <?php foreach ( $categories as $category ): ?>
        <li class="promo__item promo__item<?= $category['bg_class']; ?>">
            <a class="promo__link" href="index.php?cat_id=<?= $category['id']; ?>"><?= $category['name']; ?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
    <?php foreach ( $lots as $lot ): ?>
        <?php for ( $i = 0; $i <= $page_lots; $i++ ) : ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?= $lot['image']; ?>" width="350" height="260" alt="">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?= $lot['category']; ?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?= $lot['id']; ?>"><?= htmlspecialchars( $lot['title'] ); ?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?= price_formatter( $lot['start_price'] ); ?></span>
                    </div>
                    <div class="timer lot__timer <?= get_timer_class( $lot['dt_end'] ); ?>">
                        <?= get_lot_end( $lot['dt_end'] ); ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endfor; ?>
    <?php endforeach; ?>
    </ul>
    
    <?= $pager; ?>

</section>