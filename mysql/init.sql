CREATE TABLE users (
    id                          CHAR(36)                                PRIMARY KEY,
    username                    VARCHAR(50)                             NOT NULL UNIQUE,
    email                       VARCHAR(255)                            NOT NULL UNIQUE,
    password_hash               VARCHAR(255)                            NOT NULL,
    avatar                      VARCHAR(255),
    bio                         TEXT,
    created_at                  TIMESTAMP                               DEFAULT CURRENT_TIMESTAMP,
    confirm_token               VARCHAR(64)                             DEFAULT NULL UNIQUE,
    confirm_token_expires_at    TIMESTAMP,
    is_confirmed                BOOLEAN                                 DEFAULT FALSE,
    reset_token                 VARCHAR(64)                             DEFAULT NULL UNIQUE,
    reset_token_expires_at      TIMESTAMP,
    email_notifications         BOOLEAN                                 DEFAULT TRUE,
    account_status              ENUM('pending', 'active', 'banned')     DEFAULT 'pending'
);

CREATE TABLE photos (
    id              CHAR(36)        PRIMARY KEY,
    user_id         CHAR(36)        NOT NULL,
    filename        VARCHAR(255)    NOT NULL UNIQUE,
    caption         TEXT,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE likes (
    user_id         CHAR(36)        NOT NULL,
    photo_id        CHAR(36)        NOT NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (photo_id) REFERENCES photos(id) ON DELETE CASCADE,

    PRIMARY KEY (user_id, photo_id)
);

CREATE TABLE comments (
    id              CHAR(36)        PRIMARY KEY,
    user_id         CHAR(36)        NOT NULL,
    photo_id        CHAR(36)        NOT NULL,
    content         TEXT            NOT NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (photo_id) REFERENCES photos(id) ON DELETE CASCADE
);
