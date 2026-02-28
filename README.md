# flow-symfony-boilerplate

Minimal Symfony 7.4 boilerplate for `flow4ui/flow-symfony` with:

- Flow runtime integration (`assets/flow` + `flow_options` bootstrap)
- Example Flow components (app shell, dashboard, counter widget)
- Simple user management CRUD as a Flow component
- MySQL + Docker Compose local stack

## Stack

- PHP 8.2+
- Symfony 7.4
- Doctrine ORM + Migrations
- Flow Symfony (`flow4ui/flow-symfony`)
- Vue 3 runtime used by Flow

## Quick Start (Local)

1. Install dependencies:

```bash
composer install
yarn install
```

2. Build frontend assets:

```bash
yarn dev
# or
yarn build
```

3. Configure database URL in `.env` (already set for local MySQL):

```dotenv
DATABASE_URL="mysql://app:app@127.0.0.1:3306/flow_boilerplate?serverVersion=8.0.36&charset=utf8mb4"
```

4. Run migrations and seed sample users:

```bash
php bin/console doctrine:migrations:migrate -n
php bin/console app:seed-users
```

5. Start Symfony server:

```bash
symfony serve
# then open http://127.0.0.1:8000/app
```

## Quick Start (Docker)

1. Start containers:

```bash
docker compose up -d --build
```

2. Install app dependencies inside container:

```bash
docker compose exec php composer install
docker compose exec php yarn install
docker compose exec php yarn build
```

3. Run migrations and seed data:

```bash
docker compose exec php php bin/console doctrine:migrations:migrate -n
docker compose exec php php bin/console app:seed-users
```

4. Open app:

- [http://localhost:8000/app](http://localhost:8000/app)

## Project Layout

- `src/UI/Component/**`: Flow components/pages/widgets
- `src/Entity/User.php`: simple user model for CRUD demo
- `src/Service/UserManager.php`: domain/service logic used by Flow component
- `src/Command/SeedUsersCommand.php`: sample data seed command
- `templates/app_layout.html.twig`: Flow bootstrap entrypoint
- `config/routes/flow.yaml`: Flow endpoint + SSR route import

## Notes

- This repository is intentionally generic and does not include any proprietary components from application codebases.
- Main Flow AJAX endpoint is `/_flow/endpoint` provided by the bundle routes.
