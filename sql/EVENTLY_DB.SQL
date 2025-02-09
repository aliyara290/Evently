-- Active: 1738852313284@@127.0.0.1@5432@Evently
CREATE DATABASE evently_db


CREATE TABLE role (
                      id SERIAL PRIMARY KEY,
                      name VARCHAR(50) CHECK (name IN ('admin', 'Participant', 'Organizer'))
);


CREATE TABLE users (
                       id SERIAL PRIMARY KEY,
                       firstName VARCHAR(50),
                       lastName VARCHAR(50),
                       userName VARCHAR(50),
                       email VARCHAR(50) UNIQUE,
                       password_hash VARCHAR(255),
                       profile_picture VARCHAR(50),
                       createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       is_deleted BOOLEAN DEFAULT FALSE,
                       is_suspended BOOLEAN DEFAULT FALSE
);


CREATE TABLE user_role (
                           role_id INT,
                           user_id INT,
                           PRIMARY KEY (role_id, user_id),
                           FOREIGN KEY (role_id) REFERENCES role(id) ON DELETE CASCADE,
                           FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE categories (
                            id SERIAL PRIMARY KEY,
                            name VARCHAR(50)
);


CREATE TABLE sponsorings (
                             id SERIAL PRIMARY KEY,
                             name VARCHAR(50),
                             logo VARCHAR(255)
);


CREATE TABLE event (
                       id SERIAL PRIMARY KEY,
                       user_id INT,
                       title VARCHAR(50),
                       description TEXT,
                       image VARCHAR(255),
                       category_id INT,
                       event_mode VARCHAR(20) CHECK (event_mode IN ('enligne', 'presentiel')),
                       places INT,
                       price FLOAT,
                       start_date TIMESTAMP,
                       end_date TIMESTAMP,
                       isValidate BOOLEAN,
                       event_link VARCHAR(255),
                       status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('accepted', 'rejected', 'pending')),
                       FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE event_sponsorings (
                                   event_id INT,
                                   sponsoring_id INT,
                                   PRIMARY KEY (event_id, sponsoring_id),
                                   FOREIGN KEY (event_id) REFERENCES event(id) ON DELETE CASCADE,
                                   FOREIGN KEY (sponsoring_id) REFERENCES sponsorings(id) ON DELETE CASCADE
);


CREATE TABLE ticket (
                        id SERIAL PRIMARY KEY,
                        event_id INT,
                        user_id INT,
                        type VARCHAR(20),
                        price FLOAT,
                        qr_code VARCHAR(255),
                        FOREIGN KEY (event_id) REFERENCES event(id) ON DELETE CASCADE,
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE event_order (
                             id SERIAL PRIMARY KEY,
                             ticket_id INT,
                             total_amount FLOAT,
                             status VARCHAR(20) CHECK (status IN ('pending', 'rejected', 'refunded', 'payed')),
                             payment_method VARCHAR(20) CHECK (payment_method IN ('card', 'paypal')),
                             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             FOREIGN KEY (ticket_id) REFERENCES ticket(id) ON DELETE CASCADE
);


CREATE TABLE order_details (
                               id SERIAL PRIMARY KEY,
                               order_id INT,
                               quantity INT,
                               total FLOAT,
                               FOREIGN KEY (order_id) REFERENCES event_order(id) ON DELETE CASCADE
);


CREATE TABLE payment (
                         id SERIAL PRIMARY KEY,
                         ticket_id INT,
                         amount FLOAT,
                         status VARCHAR(20) CHECK (status IN ('approuved', 'rejected', 'pending')),
                         payment_method VARCHAR(20) CHECK (payment_method IN ('card', 'paypal')),
                         payment_date TIMESTAMP,
                         FOREIGN KEY (ticket_id) REFERENCES ticket(id) ON DELETE CASCADE
);


CREATE TABLE notification (
                              id SERIAL PRIMARY KEY,
                              user_id INT,
                              content TEXT,
                              status VARCHAR(20) CHECK (status IN ('read', 'delivred')),
                              send_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                              FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

