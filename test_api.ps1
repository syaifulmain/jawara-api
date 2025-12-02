# Test Income Categories API
Write-Host "Testing Income Categories API" -ForegroundColor Yellow
Write-Host "===================================" -ForegroundColor Yellow

$baseUrl = "http://127.0.0.1:8000/api"

# Test 1: Get income category types
Write-Host "`n1. Testing GET /income-categories-types" -ForegroundColor Cyan
try {
    $response = Invoke-RestMethod -Uri "$baseUrl/income-categories-types" -Method GET
    Write-Host "✓ Success:" -ForegroundColor Green
    $response | ConvertTo-Json -Depth 3
} catch {
    Write-Host "✗ Error: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 2: Try to get categories without auth (should fail)
Write-Host "`n2. Testing GET /income-categories (without auth)" -ForegroundColor Cyan
try {
    $response = Invoke-RestMethod -Uri "$baseUrl/income-categories" -Method GET
    Write-Host "Response: $($response | ConvertTo-Json)" -ForegroundColor Yellow
} catch {
    Write-Host "✓ Expected: Authentication required" -ForegroundColor Green
}

Write-Host "`nAPI tests completed!" -ForegroundColor Yellow
Write-Host "To test authenticated endpoints:" -ForegroundColor Cyan
Write-Host "1. Login via POST /api/login" -ForegroundColor White
Write-Host "2. Use the token in Authorization: Bearer {token} header" -ForegroundColor White
Write-Host "3. Then test CRUD operations on /api/income-categories" -ForegroundColor White