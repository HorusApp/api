CREATE TABLE users (
    id serial PRIMARY KEY,
    name text NOT NULL DEFAULT '',
    email text NOT NULL DEFAULT '',
    password text NOT NULL DEFAULT '',
    token text
);

CREATE TABLE videos (
    id serial PRIMARY KEY,
    user_id int NOT NULL,
    url text NOT NULL DEFAULT '',
    title text NOT NULL DEFAULT '',
    location text NOT NULL DEFAULT '',
    FOREIGN KEY (user_id) REFERENCES users (id)
);
