FROM mariadb:latest
COPY multiverse.sql /docker-entrypoint-initdb.d/multiverse.sql