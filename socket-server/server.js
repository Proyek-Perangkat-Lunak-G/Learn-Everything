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
        socket.userId = String(userId); // Pastikan string untuk konsistensi Map
        if (!onlineUsers.has(socket.userId)) {
            onlineUsers.set(socket.userId, new Set());
        }
        onlineUsers.get(socket.userId).add(socket.id);
        console.log(`User ${userId} registered (${onlineUsers.get(socket.userId).size} connections)`);

        // Broadcast online status
        io.emit('online-users', Array.from(onlineUsers.keys()));
    });

    // Join a chat room
    socket.on('join-chat-room', (data) => {
        const { userId, partnerId } = data;
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

    // Handle chat message using rooms (Mendukung Teks 500 Karakter & Dokumen Realtime)
    socket.on('send-message', (data) => {
        // data: { senderId, receiverId, message, fileUrl, messageType }
        const { senderId, receiverId, message, fileUrl, messageType } = data;
        const type = messageType || 'text';

        // --- VALIDASI HANYA UNTUK TEKS BIASA ---
        if (type === 'text') {
            if (!message || message.trim().length === 0) {
                socket.emit('message-error', { error: 'Pesan tidak boleh kosong.' });
                return; 
            }
            if (message.length > 500) {
                socket.emit('message-error', { error: 'Pesan gagal dikirim! Maksimal 500 karakter.' });
                return; 
            }
        }

        console.log(`Message [${type}] from ${senderId} to ${receiverId}`);

        // Create room name
        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');

        // Kirim data lengkap ke room (Teks + URL file jika ada)
        io.to(roomName).emit('new-message', {
            sender_id: senderId,
            receiver_id: receiverId,
            message: message,
            file_url: fileUrl || null,
            message_type: type,
            created_at: new Date().toISOString(),
        });

        // Kirim notifikasi realtime ke receiver jika sedang di luar room
        const receiverSockets = onlineUsers.get(String(receiverId));
        if (receiverSockets) {
            receiverSockets.forEach((socketId) => {
                io.to(socketId).emit('message-notification', {
                    sender_id: senderId,
                    message: message,
                    file_url: fileUrl || null,
                    message_type: type,
                });
            });
        }
    });

    // Handle typing indicator using rooms
    socket.on('typing', (data) => {
        const { senderId, receiverId } = data;
        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');
        socket.to(roomName).emit('user-typing', { senderId: senderId });
    });

    // Stop typing indicator
    socket.on('stop-typing', (data) => {
        const { senderId, receiverId } = data;
        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');
        socket.to(roomName).emit('user-stop-typing', { senderId: senderId });
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