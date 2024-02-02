FROM postgres:13

COPY ./_docker/local/db/init-user-db.sh /docker-entrypoint-initdb.d/init-user-db.sh

RUN localedef -i ru_RU -c -f UTF-8 -A /usr/share/locale/locale.alias ru_RU.UTF-8
ENV LANG ru_RU.utf8
