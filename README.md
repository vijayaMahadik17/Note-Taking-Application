# Notes Taking App (Laravel API + Vue 3)


**Setup Needed**

 Prerequisites
- PHP 8.2+** and **Composer 2.x**
- Node.js 18+** (Node 20 recommended) and **npm 9+**
- MySQL 8.x** (or Docker for MySQL)
- Docker Desktop** or Docker Engine for containerized MySQL

**Project Structure**

.
├─ backend/                     # Laravel app (created via composer; run artisan here)
├─ frontend/                    # Vue 3 + Vite + Tailwind app
├─ postman/                     # Postman collection + environment
├─ additional_task/             # SQL + Eloquent answers
├─ docker-compose.yml           # Optional MySQL container
└─ README.md                    # (this file)
```


**Using MySQL Locally and Docker (How it works)**

 Local MySQL - Ensure a local MySQL server is running.
- Create the DB:
  ```bash
  mysql -u root -p -e "CREATE DATABASE notes_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  ```
- In `backend/.env`, **leave the local section**:
  .env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=notes_db
  DB_USERNAME=root
  DB_PASSWORD=root
  ```
- Migrate:
  ```bash
  cd backend
  php artisan migrate
  ```

Docker MySQL (from `docker-compose.yml`)
- Start MySQL in Docker (from project root):
  ```bash
  docker compose up -d db
  ```
- In `backend/.env`, **comment out the local section and use the Docker section**:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3307
  DB_DATABASE=notes_db
  DB_USERNAME=appuser
  DB_PASSWORD=apppass
  ```
- Migrate:
  ```bash
  cd backend
  php artisan migrate
  ```

> **Summary:**  
> • If someone runs **MySQL locally** → keep `root/root`, port **3306**.  
> • If someone uses **docker-compose.yml** → switch to `appuser/apppass`, port **3307**.

---

**Running the Apps**

Backend (Laravel)
cd backend
php artisan serve --host=0.0.0.0 --port=8000
API base URL: `http://localhost:8000/api`  
`

Frontend (Vue 3 + Vite)
cd frontend
npm run dev
```
Default dev URL: `http://localhost:5173`  
Ensure `frontend/.env` has:

VITE_API_BASE=http://localhost:8000/api
```

**Testing (Backend)**

cd backend
php artisan test
```

**Postman**

- `postman/NoteApp.postman_collection.json`
- `postman/NoteApp.postman_environment.json` (set `base_url` → `http://localhost:8000/api`)


**Additional Task**
 `additional_task/`:

- `additional_queries.sql` — MySQL 8 JSON queries
- `eloquent_examples.php` — Eloquent builder examples

---



