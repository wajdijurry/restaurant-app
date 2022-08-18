Restaurant APP
--
### Description:
The API Gateway for restaurant app.

---

### Installation:

1. Clone the repository:
```shell script
git clone git@github.com:wajdijurry/restaurant-app.git
```

2. Rename the file “.env.example” under “/” to “.env” then change the parameters to match your preferences, example:
```yaml
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
...
```
#### Using Docker
3. Build a new image:
```bash
docker build -t restaurant-app-image .
```

4. Run the containers
```bash
docker-compose up -d
```


If you want to scale up the workers (sync / async), you can simply run this command:
```bash
docker-compose up --scale app=num -d
```

Where “num” is the number of processes to run, example:
```bash
docker-compose up --scale app=3 -d
```

API gateway URL (You are free to change the port through .env file)
```bash
http://localhost:1007
```

FYI: Database (MySQL) info (You are free to change the port through .env file):
```bash
Host: localhost:3301 
```

#### On host machine
> Prerequisites
> - PHP >= 8.0
> - MySQL >= 8.0

3. Install dependencies
```bash
chmod +x utilities/install-dependencies.sh && ./utilities/install-dependencies.sh
```

4. Install PHP extensions
```bash
chmod +x utilities/install-php-extensions.sh && ./utilities/install-php-extensions.sh
```

5. Install composer & APP dependencies
```bash
rm -rf vendor composer.lock && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer clearcache && \
    composer install
```

6. Prepare the app
```bash
# Make artisan executable
RUN chmod +x artisan

# Flush all cache
php artisan optimize:clear

# Execute migrations
php artisan migrate

# Seeding data
php artisan db:seed
```

---
### Unit test

> You need to have `.env.testing` file to get tests run correctly
> 
#### Using docker
To run the unit test, just run this command:
```bash
docker-compose --env-file .env.testing up app-test
```

#### On host machine
```bash
vendor/bin/phpunit -c tests/phpunit.xml
```