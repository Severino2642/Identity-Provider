CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    email TEXT,
    password TEXT,
    inscription_date DATE
);

CREATE TABLE token(
    id SERIAL PRIMARY KEY,
    id_user INT,
    token TEXT,
    expiration_date TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE attempt(
    id SERIAL PRIMARY KEY,
    id_user INT,
    attempt INT,
    add_date TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE messenger_messages(
    id bigserial primary key,
    body text not null,
    headers text not null,
    queue_name varchar(255) not null,
    created_at timestamp not null,
    available_at timestamp not null,
    delivered_at timestamp default null
);
