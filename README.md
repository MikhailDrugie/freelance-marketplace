# freelance-marketplace

## Installation

1. Clone repository

2. Set up .env from .env.example.
PS. If you change web ports and app (php-fpm) ports, please, consider changing them in nginx/conf.d/default.conf, too

3. Do the docker compose up: <code>docker compose up -d --build</code>
On Linux or with direct compose client: <code>docker-compose up -d --build</code>

4. Go to the app container:
<code>docker exec -it app bash</code>

5. Run composer update:
<code>composer update</code>

6. Run npm install:
<code>npm install</code>

7. Run npm run dev:
<code>npm run dev</code>

8. Run npm run prod:
<code>npm run prod</code>

9. Create and run migrations:
- <code>php bin/console make:migration</code>
- <code>php bin/console doctrine:migrations:migrate</code>

### Done! The project should be set up. Go to localhost.