    <?php if ( $pages_count > 1 ) :  ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <?php foreach ( $pages as $page ): ?>
        <li class="pagination-item <?= ( $page == $curr_page ) ? 'pagination-item-active' : ''; ?>"><a href="index.php?page=<?= $page; ?>"><?= $page; ?></a></li>
    <?php endforeach; ?>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
      </ul>
    <?php endif; ?>