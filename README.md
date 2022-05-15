# Log Manager System

## Dependencies

- Docker: >= 20;
- Docker Compose: >= 1.29;

## Setup

<p>Run the following command on your terminal to start the project:</p>

- `docker-compose up --build`;

<p>Wait Docker finishes all the processes. This happens when you see the following information:</p>

```
logistics-manager-php-build exited with code 0
```

## Automated tests

Use the following commands to run the automated tests:

**Enter the application container:**

```
docker exec -it logistics-manager-php bash
```

**Run the tests:**

```
composer tests
```

## Important

There is a default delivery address, but you can change that by using the following variable on `.env`:

```
MIX_MAP_DIRECTIONS_DEFAULT_ADDRESS="4420 Warwick Blvd, Kansas City, MO, USA"
```

A Google Maps API key was configured on `.env` for testing, but it is expiring in some days. Use the following variable
to set your own key:

```
GOOGLE_MAPS_API_KEY=
```
