up:
	docker compose up -d
down:
	docker compose down
install:
	docker exec app composer install
	docker exec app npm install
	docker exec app npm run dev
create-db:
	docker exec app php bin/console doctrine:database:create
migrate:
	docker exec app php bin/console doctrine:migrations:migrate
messenger:
	docker exec app php bin/console messenger:consume
fixture:
	docker exec app php bin/console doctrine:fixtures:load --append