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

---
### Unit test
To run the unit test, just run this command:
```bash
docker-compose up app-test
```