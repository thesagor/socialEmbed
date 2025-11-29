@echo off
echo ========================================
echo AI Review Scraper - Quick Setup
echo ========================================
echo.

cd scraper-service

echo [1/4] Checking Node.js installation...
node --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Node.js is not installed!
    echo Please install Node.js from https://nodejs.org/
    pause
    exit /b 1
)
echo ✓ Node.js is installed
echo.

echo [2/4] Installing dependencies...
call npm install
if errorlevel 1 (
    echo ERROR: Failed to install dependencies
    pause
    exit /b 1
)
echo ✓ Dependencies installed
echo.

echo [3/4] Setting up environment file...
if not exist .env (
    copy .env.example .env
    echo ✓ Created .env file
    echo.
    echo IMPORTANT: Edit the .env file and add your:
    echo   - OpenAI API Key
    echo   - API Secret Key
    echo.
    echo Press any key to open .env file in notepad...
    pause >nul
    notepad .env
) else (
    echo ✓ .env file already exists
)
echo.

echo [4/4] Starting the service...
echo.
echo ========================================
echo Service is starting...
echo ========================================
echo.
call npm start

pause
