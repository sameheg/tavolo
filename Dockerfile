# minimal app image example (adjust for prod)
FROM php:8.3-cli
WORKDIR /app
COPY . /app
EXPOSE 8000
CMD ["php","artisan","serve","--host","0.0.0.0","--port","8000"]
