@echo off
echo Starting Healthcare System Real-time Alert Servers...
echo.

echo Starting Laravel Development Server...
start "Laravel Server" php artisan serve

echo Starting WebSocket Server...
start "WebSocket Server" node websocket-server.js

echo.
echo Both servers are now running:
echo - Laravel Server: http://127.0.0.1:8000
echo - WebSocket Server: ws://127.0.0.1:6001
echo.
echo Press any key to exit...
pause >nul