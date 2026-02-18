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

## Deploy split (Amplify frontend + EC2 backend)

If you host the frontend on Amplify (HTTPS) and the backend on EC2, set the frontend build env var:

- `VITE_API_BASE_URL` (example: `https://api.example.com`)

The backend CORS allow-list is configured with:

- `ALLOWED_ORIGINS` (comma-separated origins)

Note: if your Amplify site is served over HTTPS, your API must also be reachable over HTTPS. Browsers will block HTTPS pages from calling an HTTP API.
