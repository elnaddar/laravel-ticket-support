# Ticket Support API

Ticket Support API is a Laravel-based project designed for managing ticket support systems. It provides functionality for authentication, authorization, filtering, and relationships. The API has been documented using [Scribe](https://scribe.knuckles.wtf/) and deployed with Docker.

## Features

- **Authentication and Authorization**: Ensures secure access to the API using role-based access control.
- **Filters**: Includes query filters for efficient data retrieval.
- **Relationships**: Manages relationships between different models in the application.
- **Documentation**: Auto-generated API documentation for easy integration and usage.
- **Deployment**: Dockerized deployment for seamless scalability and portability.

## Live Deployment

The API is deployed and accessible at the following URL:

- **Base URL**: [https://ticket-support-pscp.onrender.com](https://ticket-support-pscp.onrender.com)

## API Documentation

Explore the API documentation at:

- **Docs**: [https://ticket-support-pscp.onrender.com/docs/](https://ticket-support-pscp.onrender.com/docs/)
- **Postman Collection**: [Postman Collection JSON](https://ticket-support-pscp.onrender.com/docs/collection.json)
- **OpenAPI Specification**: [OpenAPI.yaml](https://ticket-support-pscp.onrender.com/docs/openapi.yaml)

## Getting Started

### Prerequisites

- Docker installed on your machine
- Composer (for local development)
- PHP (>=8.3) and Laravel (>=11.x)
- MySQL or compatible database

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/elnaddar/laravel-ticket-support.git
   cd laravel-ticket-support
   ```

2. Set up the environment variables:
   ```bash
   cp .env.example .env
   ```
   Configure the `.env` file with your database and other environment settings.

3. Install dependencies:
   ```bash
   composer install
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

6. Start the application using Docker:
   ```bash
   docker-compose up -d
   ```

### Running Locally

If you are running the application locally (without Docker):

1. Start the development server:
   ```bash
   php artisan serve
   ```

2. Access the API at:
   ```
   http://localhost:8000
   ```

## Usage

### Authentication

Use the following endpoints to authenticate users:

- **Register**: `POST /api/register`
- **Login**: `POST /api/login`

Include the generated Bearer token in the `Authorization` header for authenticated requests.

### Example Endpoints

- **Create Ticket**: `POST /api/tickets`
- **List Tickets**: `GET /api/tickets`
- **Filter Tickets**: `GET /api/tickets?status=O`

For more details, refer to the [API Documentation](https://ticket-support-pscp.onrender.com/docs/).

## Postman Collection

Import the Postman collection for testing and exploring the API:

[Download Postman Collection JSON](https://ticket-support-pscp.onrender.com/docs/collection.json)

## OpenAPI Specification

View or use the OpenAPI specification for integration:

[OpenAPI.yaml](https://ticket-support-pscp.onrender.com/docs/openapi.yaml)

## Contributing

1. Fork the repository.
2. Create a new branch for your feature or bugfix:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add new feature"
   ```
4. Push to your fork and submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).