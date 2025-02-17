-- Active: 1738852313284@@127.0.0.1@5432@evently_db
CREATE DATABASE evently_db;

CREATE TABLE IF NOT EXISTS role (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) CHECK (
        name IN (
            'admin',
            'Participant',
            'Organizer'
        )
    )
);

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    email VARCHAR(50) UNIQUE,
    password_hash VARCHAR(255),
    bio TEXT,
    avatar TEXT,
    google_id VARCHAR(255) UNIQUE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    is_suspended BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS user_role (
    role_id INT,
    user_id INT,
    PRIMARY KEY (role_id, user_id),
    FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS sponsorings (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50),
    logo VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS region (
    id SERIAL PRIMARY KEY,
    region VARCHAR(40) NOT NULL
);

CREATE TABLE IF NOT EXISTS city (
    id SERIAL PRIMARY KEY,
    ville VARCHAR(40) NOT NULL,
    region INT NOT NULL,
    FOREIGN KEY (region) REFERENCES region (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS city_ville (
    region_id INT NOT NULL,
    city_id INT NOT NULL,
    PRIMARY KEY (region_id, city_id),
    FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS event (
    id SERIAL PRIMARY KEY,
    user_id INT,
    title VARCHAR(50),
    description TEXT,
    image VARCHAR(255),
    category_id INT,
    event_mode VARCHAR(20) CHECK (
        event_mode IN ('enligne', 'presentiel')
    ),
    places INT,
    price FLOAT,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    isValidate BOOLEAN,
    event_link VARCHAR(255),
    status VARCHAR(20) DEFAULT 'pending' CHECK (
        status IN (
            'accepted',
            'rejected',
            'pending'
        )
    ),
    region_id INT,
    city_id INT,
    FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE
);

ALTER TABLE event ADD COLUMN havePrice bool DEFAULT false;

update event set havePrice = true where id = 5;

CREATE TABLE IF NOT EXISTS event_sponsorings (
    event_id INT,
    sponsoring_id INT,
    PRIMARY KEY (event_id, sponsoring_id),
    FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE,
    FOREIGN KEY (sponsoring_id) REFERENCES sponsorings (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ticket (
    id SERIAL PRIMARY KEY,
    event_id INT,
    user_id INT,
    type VARCHAR(20),
    price FLOAT,
    qr_code VARCHAR(255),
    FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS event_order (
    id SERIAL PRIMARY KEY,
    ticket_id INT,
    total_amount FLOAT,
    status VARCHAR(20) CHECK (
        status IN (
            'pending',
            'rejected',
            'refunded',
            'payed'
        )
    ),
    payment_method VARCHAR(20) CHECK (
        payment_method IN ('card', 'paypal')
    ),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_details (
    id SERIAL PRIMARY KEY,
    order_id INT,
    quantity INT,
    total FLOAT,
    FOREIGN KEY (order_id) REFERENCES event_order (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS payment (
    id SERIAL PRIMARY KEY,
    ticket_id INT,
    amount FLOAT,
    status VARCHAR(20) CHECK (
        status IN (
            'approuved',
            'rejected',
            'pending'
        )
    ),
    payment_method VARCHAR(20) CHECK (
        payment_method IN ('card', 'paypal')
    ),
    payment_date TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS notification (
    id SERIAL PRIMARY KEY,
    user_id INT,
    content TEXT,
    status VARCHAR(20) CHECK (
        status IN ('read', 'delivred')
    ),
    send_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

alter table event add column content text;

alter table event add column event_date date;

alter table event add column event_time time;

ALTER TABLE payment RENAME TO payments;





ALTER TABLE payments 
    ADD COLUMN IF NOT EXISTS user_id INT REFERENCES users(id) ON DELETE CASCADE,
    ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(255) UNIQUE,
    ADD COLUMN IF NOT EXISTS currency VARCHAR(10) DEFAULT 'USD',
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE payments 
    ALTER COLUMN amount TYPE DECIMAL(10,2),
    ALTER COLUMN payment_method TYPE VARCHAR(50),
    ALTER COLUMN status TYPE VARCHAR(50);

select * from payments;

CREATE TABLE IF NOT EXISTS payments (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    ticket_id INT REFERENCES ticket(id) ON DELETE CASCADE,
    transaction_id VARCHAR(255) UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'USD',
    status VARCHAR(50) CHECK (status IN ('pending', 'completed', 'failed', 'refunded')) DEFAULT 'pending',
    payment_method VARCHAR(50) CHECK (payment_method IN ('stripe', 'paypal')) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from ticket;

ALTER TABLE payments 
DROP CONSTRAINT payment_payment_method_check,
ADD CONSTRAINT payment_payment_method_check CHECK (payment_method IN ('stripe', 'paypal'));
select * from users;
CREATE TYPE ticket_status AS ENUM ('pending', 'approved', 'denied');
ALTER TABLE ticket ADD COLUMN status ticket_status default 'pending' ;


select * from payments;
ALTER TABLE ticket ADD COLUMN places INT;
ALTER TABLE ticket ADD COLUMN total_price INT;

INSERT INTO role (name) VALUES 
('admin'), 
('Participant'),
('Organizer');