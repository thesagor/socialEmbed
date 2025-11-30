@echo off
echo ========================================
echo Production Deployment Preparation
echo ========================================
echo.

REM Create deployment directories
echo [1/5] Creating deployment directories...
if not exist "deployment" mkdir deployment
if not exist "deployment\wordpress-plugin" mkdir deployment\wordpress-plugin
if not exist "deployment\scraper-service" mkdir deployment\scraper-service

REM Clean WordPress Plugin
echo [2/5] Preparing WordPress plugin...
xcopy /E /I /Y "includes" "deployment\wordpress-plugin\includes\"
xcopy /E /I /Y "assets" "deployment\wordpress-plugin\assets\"
copy /Y "social-review-slider.php" "deployment\wordpress-plugin\"
copy /Y "README.md" "deployment\wordpress-plugin\"

REM Clean Scraper Service
echo [3/5] Preparing scraper service...
cd scraper-service
copy /Y "server.js" "..\deployment\scraper-service\"
copy /Y "package.json" "..\deployment\scraper-service\"
copy /Y "package-lock.json" "..\deployment\scraper-service\"
copy /Y ".env.production" "..\deployment\scraper-service\.env.example"
copy /Y ".gitignore" "..\deployment\scraper-service\"
xcopy /E /I /Y "scrapers" "..\deployment\scraper-service\scrapers\"
xcopy /E /I /Y "utils" "..\deployment\scraper-service\utils\"
cd ..

REM Create deployment documentation
echo [4/5] Creating deployment documentation...
copy /Y "DEPLOYMENT-GUIDE.md" "deployment\"
copy /Y "README.md" "deployment\"

REM Create zip files
echo [5/5] Creating zip archives...
powershell -Command "Compress-Archive -Path 'deployment\wordpress-plugin\*' -DestinationPath 'deployment\social-review-slider-plugin.zip' -Force"
powershell -Command "Compress-Archive -Path 'deployment\scraper-service\*' -DestinationPath 'deployment\scraper-service-production.zip' -Force"

echo.
echo ========================================
echo Deployment packages created!
echo ========================================
echo.
echo WordPress Plugin:
echo   - Folder: deployment\wordpress-plugin\
echo   - Zip: deployment\social-review-slider-plugin.zip
echo.
echo Scraper Service:
echo   - Folder: deployment\scraper-service\
echo   - Zip: deployment\scraper-service-production.zip
echo.
echo Deployment Guide:
echo   - File: deployment\DEPLOYMENT-GUIDE.md
echo.
echo ========================================
echo Next Steps:
echo ========================================
echo 1. Upload social-review-slider-plugin.zip to WordPress
echo 2. Deploy scraper-service-production.zip to your server
echo 3. Follow DEPLOYMENT-GUIDE.md for detailed instructions
echo.
pause
