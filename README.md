## Transaction Management API - Readme

This document serves as a guide for the Transaction Management API, a Laravel application designed to manage financial transactions between accounts.

### Features

* Create new accounts
* Perform transactions between accounts (instant or scheduled)
* Leverage an external authorization service for transaction validation
* Process scheduled transactions daily at 5:00 AM

### Technologies

* PHP 8.2 (Docker image)
* MySQL 8.0 (Docker image)
* Redis (Docker image)
* Laravel Framework

### Prerequisites

* Docker installed and running

### Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/transaction-management-api.git
```

2. Navigate to the project directory:

```bash
cd transaction-management-api
```

3. Copy the `.env.example` file to `.env`

4. Build the Docker images and start the containers:

```bash
docker-compose up -d
```

### Usage

**API Documentation:**

The API documentation is available at `http://localhost/docs` (assuming the service is running on your local machine). This provides a detailed overview of the available endpoints, request parameters, and response formats.

**Account Management:**

* **Create a new account:**

```bash
curl -X POST http://localhost/api/account -H "Content-Type: application/json" -d '{"name": "Account Name", "balance": 100.00}'
```

**Transaction Management:**

* **Create a new transaction:**

```bash
curl -X POST http://localhost/api/transaction -H "Content-Type: application/json" -d '{"sender_id": 1, "receiver_id": 2, "amount": 50.00}'
```

* **Create a scheduled transaction:**

```bash
curl -X POST http://localhost/api/transaction -H "Content-Type: application/json" -d '{"sender_id": 1, "receiver_id": 2, "amount": 50.00, "process_at": "2024-12-31"}'
```

**CLI Commands:**

* **Create a new account (CLI):**

```bash
php artisan app:account-create
```

* **Process scheduled transactions (CLI):**

```bash
php artisan app:process-transactions
```

**Note:** The CLI commands require access to the project directory on your machine.

### Additional Notes

* This API utilizes a Docker environment for ease of deployment and consistency.
* The provided Dockerfile configures Nginx as the web server and sets up a cron job to process scheduled transactions daily.
