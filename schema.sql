CREATE DATABASE doingsdone
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE `categories` (
    cat_id INT AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(128) NOT NULL UNIQUE,
    user_id INT UNSIGNED NOT NULL,
    INDEX cat_index (cat_name)
);

CREATE TABLE `tasks` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    publish_date DATE NOT NULL,
    status TINYINT UNSIGNED NOT NULL DEFAULT 0,
    name VARCHAR(255) NOT NULL,
    file_url VARCHAR(255),
    expire_date DATE NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    INDEX task_index (name)
);

CREATE TABLE `users` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(128) NOT NULL,
    name VARCHAR(32) NOT NULL,
    password VARCHAR(128) NOT NULL,
    INDEX users_index (name)
);

CREATE FULLTEXT INDEX tasks_ft_search ON tasks(name);

SELECT u.id as user_id, u.email, u.name, t.name, t.user_id, t.expire_date, t.id as task_id
FROM users u
         JOIN tasks t
              ON u.id = t.user_id
WHERE t.status = 0
  AND expire_date = CURRENT_DATE();
