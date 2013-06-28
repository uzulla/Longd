PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
DROP TABLE user_account;
CREATE TABLE user_account(
    id integer PRIMARY KEY,
    twitter_id text,
    display_name text,
    screen_name text,
    profile_image_url text,
    twitter_oauth_token text,
    twitter_oauth_token_secret text,
    created_at text,
    updated_at text
    );
DROP TABLE message;
CREATE TABLE message(
    id integer PRIMARY KEY,
    to_user_account_id text,
    from_user_account_id text,
    message text,
    is_open integer,
    created_at text,
    updated_at text
    );
COMMIT;
