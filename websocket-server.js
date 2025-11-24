// websocket-server.js (Corrected Version)

import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import Redis from 'ioredis';

// 1. Setup Servers
const app = express();
const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: "http://127.0.0.1:8000", // Your Laravel app's URL
        methods: ["GET", "POST"]
    }
});

// 2. Connect to Redis
const redis = new Redis(); // Connects to default 127.0.0.1:6379
console.log('Connecting to Redis...');

redis.on('connect', () => {
    console.log('✅ Successfully connected to Redis.');
});

redis.on('error', (err) => {
    console.error('❌ Could not connect to Redis:', err.message);
});

// 3. Subscribe to ALL Laravel channels
// We use 'psubscribe' to get all channels that Laravel might send.
// This is more flexible than just 'doctor-alerts.*'
redis.psubscribe("*");
console.log('Subscribed to all Redis channels (*).');

// 4. Listen for messages from Redis
redis.on("pmessage", (pattern, channel, message) => {
    console.log(`[Redis] Message received on channel: ${channel}`);
    
    try {
        const data = JSON.parse(message);

        // --- THIS IS THE FIX ---
        // Instead of io.emit(), we send the message *only* to the
        // specific Socket.IO "room" that matches the private channel name.
        //
        // Example:
        //   Redis channel: 'doctor-alerts.5'
        //   Socket.IO Room: 'doctor-alerts.5'
        //   Event: 'DoctorAlertEvent'
        
        io.to(channel).emit(data.event, data.data);
        
        console.log(`[Socket.IO] Emitted event '${data.event}' to room '${channel}'.`);
        // --- END OF FIX ---
        
    } catch (e) {
        console.error('Could not parse message from Redis:', message, e);
    }
});

// 6. Handle browser connections
io.on('connection', (socket) => {
    console.log(`[Socket.IO] A user connected: ${socket.id}`);
    
    // --- THIS IS THE FIX ---
    // When a browser joins a private channel (e.g., 'doctor-alerts.5'),
    // it will first get permission from Laravel. Then, it will send
    // a 'subscribe' message to us. We must add that browser's
    // socket to the matching "room" so it can receive private messages.
    
    socket.on('subscribe', (channelName) => {
        socket.join(channelName);
        // Defensive logging: channelName might be an object in some malformed cases
        try {
            console.log(`[Socket.IO] User ${socket.id} joined room: ${typeof channelName === 'string' ? channelName : JSON.stringify(channelName)}`);
        } catch (e) {
            console.log(`[Socket.IO] User ${socket.id} joined room (unserializable):`, channelName);
        }
    });
    // --- END OF FIX ---

    socket.on('disconnect', () => {
        console.log(`[Socket.IO] User disconnected: ${socket.id}`);
    });
});

// 7. Start the server
const port = 3000;
server.listen(port, () => {
    console.log(`✅ WebSocket server listening on *: ${port}`);
    console.log(`Waiting for browser connections...`);
});