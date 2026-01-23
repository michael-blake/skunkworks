# Skunkworks Hello World

## Local development

### Backend (PHP)

From the repo root:

```sh
php -S localhost:8000 -t backend/public
```

Test endpoints:

- `http://localhost:8000/health`
- `http://localhost:8000/api/hello`

### Frontend (React + Vite)

From `frontend/`:

```sh
npm install
npm run dev
```

Open:

- `http://localhost:5173`

## Container (nginx + php-fpm)

From the repo root:

```sh
docker compose up --build
```

Open:

- `http://localhost:8080`

Test endpoints:

- `http://localhost:8080/health`
- `http://localhost:8080/api/hello`

## Tests

### Backend smoke test

From the repo root:

```sh
php backend/tests/smoke_test.php
```

### Frontend unit tests

From `frontend/`:

```sh
npm install
npm test
```
