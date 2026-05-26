const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: ['http://localhost:8000', 'http://127.0.0.1:8000'],
        methods: ['GET', 'POST'],
    },
});

// Track online users: userId -> Set of socketIds
const onlineUsers = new Map();

io.on('connection', (socket) => {
    console.log(`Socket connected: ${socket.id}`);

    // User joins with their userId
    socket.on('register', (userId) => {
        socket.userId = userId;
        if (!onlineUsers.has(userId)) {
            onlineUsers.set(userId, new Set());
        }
        onlineUsers.get(userId).add(socket.id);
        console.log(`User ${userId} registered (${onlineUsers.get(userId).size} connections)`);

        // Broadcast online status
        io.emit('online-users', Array.from(onlineUsers.keys()));
    });

    // Join a chat room
    socket.on('join-chat-room', (data) => {
        const { userId, partnerId } = data;
        // Create a consistent room name (sorted IDs to ensure same room for both users)
        const roomName = [userId, partnerId].sort((a, b) => a - b).join('-');
        socket.join(roomName);
        console.log(`User ${userId} joined room: ${roomName}`);
    });

    // Leave a chat room
    socket.on('leave-chat-room', (data) => {
        const { userId, partnerId } = data;
        const roomName = [userId, partnerId].sort((a, b) => a - b).join('-');
        socket.leave(roomName);
        console.log(`User ${userId} left room: ${roomName}`);
    });

    // Handle chat message using rooms
    socket.on('send-message', (data) => {
        // data: { senderId, receiverId, message }
        const { senderId, receiverId, message } = data;
        console.log(`Message from ${senderId} to ${receiverId}: ${message}`);

        // Create room name
        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');

        // Send to room (both sender and receiver if they're in the room)
        io.to(roomName).emit('new-message', {
            sender_id: senderId,
            receiver_id: receiverId,
            message: message,
            created_at: new Date().toISOString(),
        });

        // Also send notification to receiver if they're not in the room
        const receiverSockets = onlineUsers.get(String(receiverId));
        if (receiverSockets) {
            receiverSockets.forEach((socketId) => {
                io.to(socketId).emit('message-notification', {
                    sender_id: senderId,
                    message: message,
                });
            });
        }
    });

    // Handle typing indicator using rooms
    socket.on('typing', (data) => {
        const { senderId, receiverId } = data;
        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');
        
        // Broadcast typing to room except sender
        socket.to(roomName).emit('user-typing', {
            senderId: senderId,
        });
    });

    // Stop typing indicator
    socket.on('stop-typing', (data) => {
        const { senderId, receiverId } = data;
        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');
        
        socket.to(roomName).emit('user-stop-typing', {
            senderId: senderId,
        });
    });

    // Disconnect
    socket.on('disconnect', () => {
        if (socket.userId) {
            const userSockets = onlineUsers.get(socket.userId);
            if (userSockets) {
                userSockets.delete(socket.id);
                if (userSockets.size === 0) {
                    onlineUsers.delete(socket.userId);
                }
            }
            io.emit('online-users', Array.from(onlineUsers.keys()));
        }
        console.log(`Socket disconnected: ${socket.id}`);
    });
});

// Health check
app.get('/', (req, res) => {
    res.json({
        status: 'running',
        onlineUsers: onlineUsers.size,
    });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Socket.IO server running on port ${PORT}`);
});