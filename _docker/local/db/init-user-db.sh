#!/bin/bash

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    CREATE USER gitlab_notifier;
    ALTER USER gitlab_notifier WITH PASSWORD '123456';
    CREATE DATABASE gitlab_notifier;
    GRANT ALL PRIVILEGES ON DATABASE gitlab_notifier TO gitlab_notifier;
EOSQL
