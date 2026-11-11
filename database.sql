CREATE DATABASE IF NOT EXISTS gamecenter_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gamecenter_db;

-- Таблица жанров
CREATE TABLE IF NOT EXISTS genres (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица игр
CREATE TABLE IF NOT EXISTS games (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image_data  LONGBLOB,
    genre_id    INT NULL,
    is_recommended TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_games_genre FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица пользователей (для ЛР №3)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Связь игр с пользователями и статус
ALTER TABLE games ADD COLUMN user_id INT NULL;
ALTER TABLE games ADD CONSTRAINT fk_games_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;
ALTER TABLE games ADD COLUMN status ENUM('new', 'in_progress', 'done') DEFAULT 'new';

-- Начальные жанры (те, что были)
INSERT IGNORE INTO genres (name) VALUES 
('MOBA'), ('Шутер'), ('RPG'), ('Королевская битва'), ('Стратегия');

-- Тестовые пользователи (пароль: 123456)
INSERT INTO users (full_name, login, email, password, role) VALUES 
('Админ Админович', 'admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Иван Иванов', 'user', 'user@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');п