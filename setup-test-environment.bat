@echo off
REM Change to the root folder of your project
cd /d "%~dp0"

REM Step 1: Start the Docker container for the test database
echo Starting Docker Compose...
docker-compose up -d
if %errorlevel% neq 0 (
    echo Failed to start Docker Compose
    exit /b %errorlevel%
)

REM Step 2: DROP the test database
echo Creating test database...
php bin/console doctrine:database:drop --env=test --force
if %errorlevel% neq 0 (
    echo Failed to drop test database
    exit /b %errorlevel%
)

REM Step 3: Create the test database
echo Creating test database...
php bin/console doctrine:database:create --env=test
if %errorlevel% neq 0 (
    echo Failed to create test database
    exit /b %errorlevel%
)

REM Step 4: Create the schema based on entities
echo Creating schema...
php bin/console doctrine:schema:create --env=test
if %errorlevel% neq 0 (
    echo Failed to create schema
    exit /b %errorlevel%
)

REM Step 5: Load test data fixtures
echo Loading fixtures...
echo yes | php bin/console doctrine:fixtures:load --env=test
if %errorlevel% neq 0 (
    echo Failed to load fixtures
    exit /b %errorlevel%
)

echo Environment setup complete.
pause
