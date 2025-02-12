-- Active: 1738852313284@@127.0.0.1@5432@Evently
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
    userName VARCHAR(50),
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
select * from users;
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

select * from users;