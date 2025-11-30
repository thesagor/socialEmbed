$envFile = ".env"
$apiSecret = "social-review-scraper-secret-2024-xyz789"

$content = Get-Content $envFile
$content = $content -replace "OPENAI_API_KEY=your_openai_api_key_here", "OPENAI_API_KEY=$openaiKey"
$content = $content -replace "API_SECRET_KEY=your_secret_key_here", "API_SECRET_KEY=$apiSecret"
$content = $content -replace "NODE_ENV=development", "NODE_ENV=production"

$content | Set-Content $envFile

Write-Host "Environment file configured successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Your configuration:" -ForegroundColor Cyan
Write-Host "  - OpenAI API Key: Configured" -ForegroundColor Green
Write-Host "  - API Secret Key: $apiSecret" -ForegroundColor Yellow
Write-Host "  - Port: 3000" -ForegroundColor White
Write-Host "  - Environment: production" -ForegroundColor White
Write-Host ""
Write-Host "IMPORTANT: Save this API Secret Key!" -ForegroundColor Yellow
Write-Host "You will need it in WordPress settings: $apiSecret" -ForegroundColor Yellow
Write-Host ""
Write-Host "Next step: Run npm start to start the service" -ForegroundColor Cyan
