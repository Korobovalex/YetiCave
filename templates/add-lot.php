		<!-- Форма добавления нового лота -->
		<form class="form form--add-lot container <?= !empty( $errors ) ? 'form--invalid' : ''; ?> " name="add-lot" action="add.php" method="POST" enctype="multipart/form-data">
			<h2>Добавление лота</h2>
			<div class="form__container-two">
				<div class="form__item <?= !empty( $errors['title'] ) ? 'form_item--invalid' : ''; ?>">
					<label for="lot-name">Наименование <sup>*</sup></label>
					<input id="lot-name" type="text" name="title" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $lot['title'] ) && (string) $lot['title'] ) ? $lot['title'] : ''; ?>" placeholder="Введите наименование лота">
					<span class="form__error">Введите наименование лота</span>
				</div>
				<div class="form__item <?= !empty( $errors['category'] ) ? 'form_item--invalid' : ''; ?>">
					<label for="category">Категория <sup>*</sup></label>
					<select id="category" name="category">
						<option>Выберите категорию</option>
										<?php foreach ( $categories as $category ): ?>
												<option value="<?= $category['id']; ?>" <?= !empty($lot['category']) && (int) $category['id'] === (int) $lot['category'] ? 'selected'  : '' ?>><?= $category['name']; ?></option>
										<?php endforeach; ?>
					</select>
					<span class="form__error">Выберите категорию</span>
				</div>
			</div>
			<div class="form__item form__item--wide <?= !empty( $errors['description'] ) ? 'form_item--invalid' : ''; ?>">
				<label for="message">Описание <sup>*</sup></label>
				<textarea id="message" name="description" placeholder="Напишите описание лота" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $lot['description'] ) && (string) $lot['description'] ) ? $lot['description'] : ''; ?>"></textarea>
				<span class="form__error">Напишите описание лота</span>
			</div>
			<div class="form__item form__item--file <?= !empty( $errors['image'] ) ? 'form_item--invalid' : ''; ?>">
				<label>Изображение <sup>*</sup></label>
				<div class="form__input-file">
					<input class="visually-hidden" name="image" type="file" id="lot-img" value="">
					<label for="lot-img">
						Добавить
					</label>
				</div>
			</div>
			<div class="form__container-three">
				<div class="form__item form__item--small <?= !empty( $errors['start_price'] ) ? 'form_item--invalid' : ''; ?>">
					<label for="lot-rate">Начальная цена <sup>*</sup></label>
					<input id="lot-rate" type="text" name="start_price" placeholder="0" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' ? $lot['start_price'] : ''; ?>">
					<span class="form__error">Введите начальную цену</span>
				</div>
				<div class="form__item form__item--small <?= !empty( $errors['bet_step'] ) ? 'form_item--invalid' : ''; ?>">
					<label for="lot-step">Шаг ставки <sup>*</sup></label>
					<input id="lot-step" type="text" name="bet_step" placeholder="0" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' ? $lot['bet_step'] : ''; ?>">
					<span class="form__error">Введите шаг ставки</span>
				</div>
				<div class="form__item <?= !empty( $errors['dt_end'] ) ? 'form_item--invalid' : ''; ?>">
					<label for="lot-end">Дата окончания торгов <sup>*</sup></label>
					<input class="form__input-date" id="lot-end" type="text" name="dt_end" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $_SERVER['REQUEST_METHOD'] === 'POST' && ( !empty( $lot['dt_end'] ) && (string) $lot['dt_end'] ) ? $lot['dt_end'] : ''; ?>">
					<span class="form__error">Введите дату завершения торгов</span>
				</div>
			</div>
			<span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
			<ul>
      		<?php foreach ($errors as $key => $error) :?>
        		<li class="form__error--item"><?= $error ?></li>
      		<?php endforeach; ?>
      		</ul>
			<button type="submit" class="button">Добавить лот</button>
		</form>