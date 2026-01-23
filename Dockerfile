FROM node:20-alpine AS frontend_build

WORKDIR /app/frontend

COPY frontend/package.json frontend/package-lock.json* ./
RUN npm ci

COPY frontend/ ./
RUN npm run build


FROM php:8.3-fpm-alpine AS runtime

RUN apk add --no-cache nginx supervisor

WORKDIR /var/www

COPY backend/public/ /var/www/backend/public/
COPY --from=frontend_build /app/frontend/dist/ /var/www/frontend/

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 8080

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
