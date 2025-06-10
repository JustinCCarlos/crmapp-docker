# CRM Backend Docker Compose Setup

## Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/JustinCCarlos/crmapp-docker
   cd crmapp-docker
   ```

2. Copy environment files:
   ```bash
   cp .env.example .env
   cp laravel.env.example laravel.env
   ```
3. Update passwords in .env and laravel.env
  *(Optional: Change ports in compose.yaml if needed)*

5. Build the containers:
   ```bash
   docker compose -f compose.yaml build
   ```

6. Start the containers:
   ```bash
   docker compose -f compose.yaml up -d
   ```

7. Start database migrations:
   ```bash
   docker compose exec api php artisan migrate
   ```
8. Populate the database:
   ```bash
   docker compose exec api php artisan db:seed --class=CustomerSeeder
   ```

9. Synchronize the database to Elasticsearch:
   ```bash
   docker compose exec api php artisan customers:sync-elasticsearch
   ```

# API ENDPOINTS

| Method | Endpoint              | Description                                      |
|--------|-----------------------|--------------------------------------------------|
| GET    | /customers            | Get all customers, with optional filters         |
| GET    | /customers/{id}       | Get a specific customer                          |
| POST   | /customers            | Create a new customer                            |
| PUT    | /customers/{id}       | Update an existing customer                      |
| DELETE | /customers/{id}       | Delete a customer                                |

## GET /customers optional filters
- name - filter by customer name
- email - filter by customer email 

Example Usage:
  ```http
  GET /customers?name=Justin
  GET /customers?email=example.com
  GET /customers?name=Justin&email=example.com
  ```
