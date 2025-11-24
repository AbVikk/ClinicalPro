const { exec } = require('child_process');
const fs = require('fs');

// Check if package.json exists
if (!fs.existsSync('./package.json')) {
  // Create package.json if it doesn't exist
  fs.writeFileSync('./package.json', JSON.stringify({
    "name": "healthcare-websocket-server",
    "version": "1.0.0",
    "description": "WebSocket server for healthcare system",
    "main": "websocket-server.js",
    "scripts": {
      "start": "node websocket-server.js"
    },
    "dependencies": {
      "ws": "^8.18.0"
    }
  }, null, 2));
}

// Install dependencies
exec('npm install', (error, stdout, stderr) => {
  if (error) {
    console.error(`Error installing dependencies: ${error}`);
    return;
  }
  
  console.log('Dependencies installed successfully!');
  console.log(stdout);
  
  if (stderr) {
    console.error(`stderr: ${stderr}`);
  }
});