FROM php:latest
RUN apt-get update && apt-get install git -y
COPY . /usr/src/infinity
WORKDIR /usr/src/infinity/public/api
EXPOSE 8000
CMD php -S 0.0.0.0:8000 index.php