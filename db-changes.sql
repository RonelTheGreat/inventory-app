CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE products ADD COLUMN category_id INT UNSIGNED AFTER id;
ALTER TABLE products ADD COLUMN `description` VARCHAR(255) AFTER `name`;
ALTER TABLE products ADD COLUMN price DECIMAL(19, 2) NOT NULL DEFAULT 0 AFTER `description`;

CREATE TABLE product_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    `url` TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sizes (
    `code` VARCHAR(16) PRIMARY KEY,
    `label` VARCHAR(64) NOT NULL,
    `order` TINYINT NOT NULL
);
INSERT INTO sizes
VALUES ('xs', 'Extra Small', 1),
       ('s', 'Small', 2),
       ('m', 'Medium', 3),
       ('l', 'Large', 4),
       ('xl', 'Extra Large', 5),
       ('2xl', 'Extra Extra Large', 6),
       ('fs', 'Free Size', 7);

ALTER TABLE products ADD COLUMN size VARCHAR(16) NOT NULL DEFAULT 'fs' AFTER price;
ALTER TABLE products ADD COLUMN stocks INT UNSIGNED NOT NULL DEFAULT 0 AFTER size;
