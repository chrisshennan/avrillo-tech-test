# Avrillo Conveyancing Technical Test

## Brief
- The test has to be completed using Lavarel.
- The challenge will contain a few core features most applications have. That includes connecting to an API, basic MVC using Laravel, exposing an API, and finally tests.
- The API we want you to connect to is https://kanye.rest/
- The application should have the following features
    - A rest API that shows 5 random Kayne West quotes (must)
    - There should be an endpoint to refresh the quotes and fetch the next 5 random quotes (must)
    - Authentication for these APIs should be done with an API token, not using any package. (must)
    - The above features are tested with Feature tests (must)
    - The above features are tested with Unit tests (nice to have)
    - Provide a README on how we can set up and test the application (must)
    - Implementation of API using Laravel Manager Design Pattern (Plus)
    - Making third-party API response quick by cache(Plus)

## The Solution

### Requirements
- Docker / Docker Compose

### Usage

This project has been built using docker and containers for nginx and PHP 8.2 for consistency between environments.  You can view the configuration for this in the `docker` directory.

To start the project and make the API available, complete the following commands.

```bash
## Clone the repository and switch to the correct branch
git clone https://github.com/chrisshennan/avrillo-tech-test.git
cd avrillo-tech-test
git checkout feature/tech-test
```

**Create a `docker/.env` file with the following contents**
```bash
XDEBUG_REMOTE_HOST=host.docker.internal
PHP_IMAGE=idlemoments/php:8.2-fpm-dev
PHP_IDE_CONFIG_SERVER_NAME=avrillo
```

Run the following command from the host
```bash
# Add the .env file and add the API key
cp .env.example .env
echo "APP_API_KEY=this-should-be-a-random-string" >> .env

## Install composer dependencies
docker run --rm -v $PWD:/app composer install

## Start the docker containers
docker-compose --project-directory=./docker -f ./docker/docker-compose.yml up -d

## Resolve permission
docker exec -it docker_php_1 bash -c "cd /app && chown -R www-data:www-data storage/ bootstrap/cache/"
```

The API should now be available on https://locahost:8888. It has been configured to run on port 8888 to avoid conflicting with any processes that may be running on the default port (80).

Note: I would normally have the project structured as follows (see https://github.com/idlemoments/lamp-skeleton)

- code
- database
- docker

which would allow the code and infrastructure to be separated out, but for simplicity, and to make the setup & running of this project easier, I've opted to include it within the project.

### Tests

This project includes feature and unit tests.  To run the tests, run the following command.

```bash
docker exec -it docker_php_1 bash -c "cd /app && XDEBUG_MODE=off ./vendor/bin/phpunit"
```

Alternatively, you can connect to the container and run the tests from there.

This project has also been configured with GitHub Actions to run the tests when the code is pushed and you can see the test runs via https://github.com/chrisshennan/avrillo-tech-test/actions.  The configuration for this can be found in `.github/workflows/ci.yml`

### API Endpoints

You can use the following API endpoints.  A postman collection has been included in the `examples` directory which can be used to test the endpoints.

### GET /api/quote/show

Headers:
- x-api-key (required) - This is defined in the .env file under APP_API_KEY (default: this-should-be-a-random-string)

This will return 5 random Kayne West quotes

**Sample cURL request**
```bash
curl \
  --location 'http://localhost:8888/api/quotes/show' \
  --header 'x-api-key: this-should-be-a-random-string'
```

### POST /api/quote/refresh

Headers:
- x-api-key (required) - This is defined in the .env file under APP_API_KEY (default: this-should-be-a-random-string)

This will refresh the quotes and return 5 new random Kayne West quotes.

Note: I've gone back and forth over the correct VERB to use for this endpoint.  I've opted for POST as it changes the state of the data, but as there is no payload required, I can see the argument for using GET.

**Sample cURL request**
```bash
curl \
  --request POST \
  --location 'http://localhost:8888/api/quotes/refresh' \
  --header 'x-api-key: this-should-be-a-random-string'
```

## Points of Interest

### Api Key Validation

API key validation is performed by a middleware class (app/Http/Middleware/ApiKeyValidation.php) so by the time the code reaches the controller, we know the API key is valid.

I'm also making use of the return-early-return-often approach to reduce the amount of nesting in the code.  This aids with readability and makes it easier to follow the flow of the code.  Once the conditional statements have been passed, we know the API key is valid and have everything we need to continue and we don't have the cognitive load of having to remember what condition we're nested in.

### Testing

Feature and unit tests have been written.  The unit tests provide more comprehensive coverage of the code, and the feature tests are a little more high-level but ensure that the API is working as expected.

I've also mocked out the external API calls to api.kanye.rest so that live requests aren't made during testing.  This means that the tests will still pass even if the external API is down or unavailable.

Tests are run automatically by GitHub actions (See .github/workflows/ci.yml for configuration) and you can verify these are running and tests are passing via https://github.com/chrisshennan/avrillo-tech-test/actions.

### Quote / QuoteCollection

I've added the `Quote` and `QuoteCollection` classes to define how we want to work with quotes within the application.  This allows us to integrate with other quote providers in the future without having to change the way we work with quotes within the application as the provider's custom formats are transformed into our consistent format before processing.

### Quote Manager / Quote Service

I've separated out the code into Quote Manager and Quote Service classes.  This allows us to easily swap out the quote services in the future without changing our core code (which interacts with the QuotManager).

To add a new quote service, we would create the QuoteService class, the relevant tests, and then update the QuoteManager to inject this new service instead of the `KanyeRestQuoteService`.

### Further Development

If we were to continue the development of this project, I would look to add the following.

- Replace `Cache::get()` and `Cache::put()` with events & event listeners so we can add or change how we cache i.e. we could introduce an event listener that caches to MySQL, Redis, or JSON store.  The core functionality wouldn't change but we could easily change how we cache the data.
- Add events and event listeners for pre & post Guzzle requests so we can log the requests and responses to allow for debugging API errors i.e. store the request and response bodies in an AWS S3 bucket.
- Add sentry.io to log errors and exceptions for easier debugging
- Remove the hardcoded API endpoint from KanyeRestQuoteService and move it into the .env file
- Add https://github.com/nelmio/NelmioApiDocBundle to generate API documentation from the code
- Move the custom app config variable out of the `config/app.php` file so that the core framework and custom variable as stored/configured separately.
- Add PHPStan into the CI/CD pipeline to ensure code quality
- Resolve file permissions issues with the docker container so that host changes and internal container changes are owned by the same user and don't conflict with each other.

## Time Spent

The following is a rough breakdown of the time I've spent on this test.

- Environment Setup (approx 1 hour)
    - Configure the docker environment with LEMP
    - Initialise the GitHub repository and push an initial commit
    - Add Github actions to run unit tests

- Stub endpoints (approx 1 hour)
    - Stub endpoints for the 2 required endpoints
    - Adding middleware to validate the API key
    - Add unit tests for the API key validation middleware
    - Update feature test to include the x-api-key header now required.

- Building out endpoints and integrating with Kanye.rest (approx 1.5 hours)
    - Building out the show and refresh endpoints
    - Allowing the show endpoint to refresh the quotes in the case of a cold start
    - Adding a QuoteManager and KanyeRestQuoteService to separate out concerns and allow business logic to interact with the manager so new quote services can be added or swapped in easily.
    - Add QuoteCollection and Quote DTO classes to allow data to be managed in a consistent format and allow easier integration with other quote services at a later date.

- Building out the unit and features tests (approx 1.5 hours)
    - Adding feature tests and mocking out the external API calls to api.kanye.rest so live requests aren't made during testing
    - Add unit tests for the manager, service, DTO and collection classes

- Documentation (approx 1.5 hour)
    - Creating this README file
    - Adding a Postman collection to the examples directory
    - Testing the setup instructions
