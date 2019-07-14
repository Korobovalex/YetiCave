
-- Создание Базы данных проекта Yeti Cave
CREATE DATABASE `853141-yeticave-9`
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE `853141-yeticave-9`;

-- Создание таблицы категорий
CREATE TABLE categories (
	id			INT AUTO_INCREMENT PRIMARY KEY,

	name		VARCHAR(255) NOT NULL UNIQUE,
	bg_class		VARCHAR(255) NOT NULL
);

CREATE INDEX c_name ON categories(name);

-- Создание таблицы лотов
CREATE TABLE lots (
	id			INT AUTO_INCREMENT PRIMARY KEY,

	title		VARCHAR(255) NOT NULL,
	description TEXT NOT NULL,
	image		VARCHAR(255) NOT NULL,
	start_price	DECIMAL(10, 2) NOT NULL,
	bet_step	INT NOT NULL,

	cat_id		INT NOT NULL,
	author_id	INT NOT NULL,
	winner_id	INT DEFAULT NULL,

	dt_add		DATETIME DEFAULT CURRENT_TIMESTAMP,
	dt_end		DATETIME NOT NULL
);

-- Создание индекса для полнотекстового поиска лотов по названию и описанию
CREATE FULLTEXT INDEX lot_search ON lots( title, description );

-- Создание таблицы ставок
CREATE TABLE bets (
	id			INT AUTO_INCREMENT PRIMARY KEY,
	amount 		INT NOT NULL,

	user_id		INT NOT NULL,
	lot_id		INT NOT NULL,

	dt_add		DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Создание таблицы пользователей
CREATE TABLE users (
	id			INT AUTO_INCREMENT PRIMARY KEY,

	email		VARCHAR(255) NOT NULL UNIQUE,
	name		VARCHAR(255) NOT NULL,
	password	VARCHAR(255) NOT NULL,
	avatar		VARCHAR(255) DEFAULT NULL,
	contacts	TEXT NOT NULL,

	dt_add 		DATETIME DEFAULT CURRENT_TIMESTAMP
);