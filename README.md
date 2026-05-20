<pre>
<?php print_r($_SESSION); ?>
</pre>
session debugging   



CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    img_url VARCHAR(255),
    role ENUM('customer', 'employee', 'admin') NOT NULL DEFAULT 'customer',
    subscribed BOOLEAN NOT NULL DEFAULT 0,
    password VARCHAR(255) NOT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    employee_id INT UNSIGNED NULL,
    booking_ref VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    scheduled_start DATETIME NOT NULL,
    status ENUM(
        'confirmed',
        'completed',
        'cancelled',
        'no_show'
    ) NOT NULL DEFAULT 'confirmed',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
);

CREATE TABLE employees (
    employee_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(100) NOT NULL,
    salary DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    when_hired DATE NOT NULL
);

CREATE TABLE services (
    service_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    img_url VARCHAR(255)
    DEFAULT 'https://res.cloudinary.com/dgz5gpe5z/image/upload/v1776150413/IMG_2486_ghbqn3.jpg',
    is_active BOOLEAN NOT NULL DEFAULT 1
);

CREATE TABLE booking_services (
    booking_service_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    scheduled_at DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration INT NOT NULL,
    notes TEXT
);

CREATE TABLE capabilities (
    capability_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id INT UNSIGNED NOT NULL,
    employee_id INT UNSIGNED NOT NULL
);

CREATE TABLE categories (
    category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('product', 'service') NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    img_url VARCHAR(255)
    DEFAULT 'https://res.cloudinary.com/dgz5gpe5z/image/upload/v1776150413/IMG_2486_ghbqn3.jpg'
);

CREATE TABLE products (
    product_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    sku VARCHAR(100) NOT NULL UNIQUE,
    cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    quantity INT NOT NULL DEFAULT 0,
    img_url VARCHAR(255),
    is_active BOOLEAN NOT NULL DEFAULT 1
);








-- BOOKINGS

ALTER TABLE bookings
ADD CONSTRAINT fk_booking_user
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE SET NULL;

ALTER TABLE bookings
ADD CONSTRAINT fk_booking_employee
FOREIGN KEY (employee_id)
REFERENCES employees(employee_id)
ON DELETE SET NULL;


-- EMPLOYEES

ALTER TABLE employees
ADD CONSTRAINT fk_employee_user
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE CASCADE;


-- SERVICES

ALTER TABLE services
ADD CONSTRAINT fk_service_category
FOREIGN KEY (category_id)
REFERENCES categories(category_id)
ON DELETE SET NULL;


-- PRODUCTS

ALTER TABLE products
ADD CONSTRAINT fk_product_category
FOREIGN KEY (category_id)
REFERENCES categories(category_id)
ON DELETE SET NULL;


-- BOOKING SERVICES

ALTER TABLE booking_services
ADD CONSTRAINT fk_bookingservice_booking
FOREIGN KEY (booking_id)
REFERENCES bookings(booking_id)
ON DELETE CASCADE;

ALTER TABLE booking_services
ADD CONSTRAINT fk_bookingservice_service
FOREIGN KEY (service_id)
REFERENCES services(service_id)
ON DELETE CASCADE;


-- CAPABILITIES

ALTER TABLE capabilities
ADD CONSTRAINT fk_capability_service
FOREIGN KEY (service_id)
REFERENCES services(service_id)
ON DELETE CASCADE;

ALTER TABLE capabilities
ADD CONSTRAINT fk_capability_employee
FOREIGN KEY (employee_id)
REFERENCES employees(employee_id)
ON DELETE CASCADE;