

CREATE DATABASE IF NOT EXISTS ttrpg_manager
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ttrpg_manager;


CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS characters (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        INT UNSIGNED  NOT NULL,
    character_name VARCHAR(100)  NOT NULL,
    class          VARCHAR(80)   NOT NULL,
    strength       TINYINT UNSIGNED NOT NULL DEFAULT 10,
    agility        TINYINT UNSIGNED NOT NULL DEFAULT 10,
    presence       TINYINT UNSIGNED NOT NULL DEFAULT 10,
    abilities      JSON          NOT NULL,
    created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_characters_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    INDEX idx_characters_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
