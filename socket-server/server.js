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

// Menyimpan koneksi online users
const onlineUsers = new Map();

io.on('connection', (socket) => {
    console.log(`Socket connected: ${socket.id}`);

    // Registrasi User
    socket.on('register', (userId) => {
        socket.userId = String(userId);
        if (!onlineUsers.has(socket.userId)) {
            onlineUsers.set(socket.userId, new Set());
        }
        onlineUsers.get(socket.userId).add(socket.id);
        console.log(`User ${userId} registered (${onlineUsers.get(socket.userId).size} connections)`);

        // Broadcast status online
        io.emit('online-users', Array.from(onlineUsers.keys()));
    });

    // Masuk ke room chat
    socket.on('join-chat-room', (data) => {
        const { userId, partnerId } = data;
        const roomName = [userId, partnerId].sort((a, b) => a - b).join('-');
        socket.join(roomName);
        console.log(`User ${userId} joined room: ${roomName}`);
    });

    // Keluar dari room chat
    socket.on('leave-chat-room', (data) => {
        const { userId, partnerId } = data;
        const roomName = [userId, partnerId].sort((a, b) => a - b).join('-');
        socket.leave(roomName);
        console.log(`User ${userId} left room: ${roomName}`);
    });

    // Handle pengiriman pesan (SINKRONISASI TOTAL: Menggunakan standar kunci variabel Laravel)
    socket.on('send-message', (data) => {
        const { senderId, receiverId, message, attachment_url, attachment_name, messageType } = data;
        const type = (attachment_url || messageType === 'file') ? 'file' : 'text';

        // Validasi teks 500 karakter di sisi server
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

        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');

        // Broadcast data lengkap ke room dengan nama variabel yang konsisten
        io.to(roomName).emit('new-message', {
            sender_id: senderId,
            receiver_id: receiverId,
            message: message,
            attachment_url: attachment_url || null,
            attachment_name: attachment_name || null, // Terpancar secara realtime ke penerima
            message_type: type,
            created_at: new Date().toISOString(),
        });

        // Kirim notifikasi realtime jika penerima sedang di luar room
        const receiverSockets = onlineUsers.get(String(receiverId));
        if (receiverSockets) {
            receiverSockets.forEach((socketId) => {
                io.to(socketId).emit('message-notification', {
                    sender_id: senderId,
                    message: message,
                    attachment_url: attachment_url || null,
                    attachment_name: attachment_name || null,
                    message_type: type,
                });
            });
        }
    });

    // Handle typing indicator
    socket.on('typing', (data) => {
        const { senderId, receiverId } = data;
        const roomName = [senderId, receiverId].sort((a, b) => a - b).join('-');
        socket.to(roomName).emit('user-typing', { senderId: senderId });
    });

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