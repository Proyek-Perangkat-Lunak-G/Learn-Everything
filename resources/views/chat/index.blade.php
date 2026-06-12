<x-app-layout>
<div class="flex flex-col" style="height: calc(100vh - 64px);">

    {{-- Top bar --}}
    <div class="bg-white border-b flex-shrink-0">
        <div class="max-w-5xl mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900">Chat</h1>
        </div>
    </div>

    {{-- Chat panel (fills remaining height) --}}
    <div class="flex-1 overflow-hidden max-w-5xl w-full mx-auto px-4 py-4">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden h-full flex">

            {{-- Sidebar --}}
            <div class="w-72 flex-shrink-0 border-r flex flex-col h-full">
                <div class="p-4 border-b bg-gray-50 flex-shrink-0">
                    <h3 class="font-semibold text-gray-900 text-sm">Percakapan</h3>
                </div>
                <div class="flex-1 overflow-y-auto">

                    {{-- Active chat partners --}}
                    @foreach($chatPartners as $partner)
                        <a href="{{ route('chat.show', $partner) }}"
                           class="flex items-center gap-3 px-4 py-3 border-b hover:bg-blue-50 transition {{ isset($receiver) && $receiver->id === $partner->id ? 'bg-blue-50 border-l-4 border-l-blue-600' : '' }}">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-white">{{ strtoupper(substr($partner->name, 0, 1)) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 text-sm truncate">{{ $partner->name }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst($partner->role) }}</p>
                            </div>
                        </a>
                    @endforeach

                    {{-- Chat Marketplace: tutors + sellers not yet chatted --}}
                    @php
                        $marketplaceContacts = collect();
                        if(isset($availableTutors)) $marketplaceContacts = $marketplaceContacts->merge($availableTutors->map(fn($u) => ['user' => $u, 'label' => 'Tutor']));
                        if(isset($availableSellers)) $marketplaceContacts = $marketplaceContacts->merge($availableSellers->map(fn($u) => ['user' => $u, 'label' => 'Penjual']));
                    @endphp
                    @if($marketplaceContacts->isNotEmpty())
                        <div class="px-4 py-2 bg-gray-50 border-b">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Chat Marketplace</h4>
                        </div>
                        @foreach($marketplaceContacts as $contact)
                            <a href="{{ route('chat.show', $contact['user']) }}"
                               class="flex items-center gap-3 px-4 py-3 border-b hover:bg-gray-50 transition {{ isset($receiver) && $receiver->id === $contact['user']->id ? 'bg-blue-50 border-l-4 border-l-blue-600' : '' }}">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-white">{{ strtoupper(substr($contact['user']->name, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 text-sm truncate">{{ $contact['user']->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $contact['label'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif

                </div>
            </div>

            {{-- Chat Area --}}
            <div class="flex-1 flex flex-col min-w-0 h-full">
                @if(isset($receiver))
                    {{-- Header --}}
                    <div class="flex-shrink-0 px-4 py-3 border-b bg-gray-50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-white">{{ strtoupper(substr($receiver->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $receiver->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($receiver->role) }}</p>
                        </div>
                    </div>

                    {{-- Messages (scrollable) --}}
                    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 min-h-0">
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl {{ $msg->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                    @if($msg->message)
                                        <p class="text-sm break-words">{{ $msg->message }}</p>
                                    @endif
                                    @if($msg->attachment)
                                        @php
                                            $ext = strtolower(pathinfo($msg->attachment_name ?? $msg->attachment, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                        @endphp
                                        @if($isImage)
                                            <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $msg->attachment) }}" alt="{{ $msg->attachment_name }}" class="mt-2 max-w-xs rounded-lg border">
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank" download="{{ $msg->attachment_name }}"
                                               class="flex items-center gap-2 mt-2 px-3 py-2 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-blue-700 hover:bg-blue-800' : 'bg-white border hover:bg-gray-50' }} transition">
                                                <svg class="w-5 h-5 flex-shrink-0 {{ $msg->sender_id === auth()->id() ? 'text-blue-200' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span class="text-xs truncate max-w-[180px] {{ $msg->sender_id === auth()->id() ? 'text-blue-100' : 'text-blue-600' }}">{{ $msg->attachment_name ?? 'File' }}</span>
                                            </a>
                                        @endif
                                    @endif
                                    <p class="text-xs mt-1 {{ $msg->sender_id === auth()->id() ? 'text-blue-200' : 'text-gray-400' }}">{{ $msg->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- File preview bar --}}
                    <div id="file-preview-bar" class="hidden flex-shrink-0 px-4 py-2 border-t bg-yellow-50 items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <span id="file-preview-name" class="text-sm text-yellow-800 flex-1 truncate"></span>
                        <button type="button" id="remove-file" class="text-yellow-600 hover:text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Input (always at bottom) --}}
                    <div class="flex-shrink-0 px-4 py-3 border-t bg-white">
                        <form id="chat-form" class="flex items-center gap-2">
                            <label for="chat-file" class="cursor-pointer text-gray-400 hover:text-blue-600 transition flex-shrink-0" title="Kirim file">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <input type="file" id="chat-file" class="hidden" accept="*/*">
                            </label>
                            <input type="text" id="chat-input" placeholder="Ketik pesan..."
                                class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                autocomplete="off">
                            <button type="submit"
                                class="flex-shrink-0 bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition flex items-center gap-1 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Kirim
                            </button>
                        </form>
                    </div>

                @else
                    <div class="flex-1 flex items-center justify-center text-gray-400">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p class="text-sm">Pilih percakapan untuk mulai chat</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@if(isset($receiver))
    @push('scripts')
    <script src="http://127.0.0.1:3000/socket.io/socket.io.js"></script>
    <script>
        const socket = io('http://127.0.0.1:3000');
        const userId = {{ auth()->id() }};
        const receiverId = {{ $receiver->id }};
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatFile = document.getElementById('chat-file');
        const filePreviewBar = document.getElementById('file-preview-bar');
        const filePreviewName = document.getElementById('file-preview-name');
        const removeFileBtn = document.getElementById('remove-file');

        socket.emit('register', userId);
        socket.emit('join-chat-room', { userId, partnerId: receiverId });

        // Scroll to bottom on load
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // File preview UI toggler
        chatFile.addEventListener('change', () => {
            if (chatFile.files.length > 0) {
                filePreviewBar.classList.remove('hidden');
                filePreviewBar.classList.add('flex');
                filePreviewName.textContent = chatFile.files[0].name;
            }
        });

        removeFileBtn.addEventListener('click', () => {
            chatFile.value = '';
            filePreviewBar.classList.add('hidden');
            filePreviewBar.classList.remove('flex');
            filePreviewName.textContent = '';
        });

        // Tangkap pesan error dari WebSocket server (misal: lebih dari 500 karakter)
        socket.on('message-error', (data) => {
            alert(data.error);
        });

        // Socket listener untuk pesan masuk baru secara realtime
        socket.on('new-message', (data) => {
            if (data.sender_id == receiverId && data.receiver_id == userId) {
                appendMessage(data.sender_id, data.message, data.file_url, data.message, data.created_at);
            }
        });

        function appendMessage(senderId, message, attachmentUrl, attachmentName, createdAt) {
            const isMine = senderId == userId;
            
            const fileTarget = attachmentUrl || attachmentName || '';
            const ext = fileTarget.split('.').pop().toLowerCase();
            const isImage = ['jpg','jpeg','png','gif','webp'].includes(ext);
            
            let attachHtml = '';
            if (attachmentUrl) {
                if (isImage) {
                    attachHtml = `<a href="${attachmentUrl}" target="_blank"><img src="${attachmentUrl}" class="mt-2 max-w-xs rounded-lg border"></a>`;
                } else {
                    const color = isMine ? 'bg-blue-700 text-blue-100' : 'bg-white border text-blue-600';
                    attachHtml = `<a href="${attachmentUrl}" target="_blank" download="${attachmentName || 'file'}" class="flex items-center gap-2 mt-2 px-3 py-2 rounded-lg ${color} transition text-xs truncate max-w-[200px]">${attachmentName || 'File'}</a>`;
                }
            }
            
            const time = new Date(createdAt).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
            wrapper.innerHTML = `<div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl ${isMine ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900'}">
                ${message && !attachmentUrl ? `<p class="text-sm break-words">${message}</p>` : ''}
                ${message && attachmentUrl ? `<p class="text-sm break-words mb-1">${message}</p>` : ''}
                ${attachHtml}
                <p class="text-xs mt-1 ${isMine ? 'text-blue-200' : 'text-gray-400'}">${time}</p>
            </div>`;
            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Handle pengiriman form via AJAX + Pemicu WebSocket Realtime
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            const file = chatFile.files[0];
            if (!message && !file) return;

            // Validasi lokal frontend jika hanya teks biasa yang > 500 karakter
            if (!file && message.length > 500) {
                alert('Pesan gagal dikirim! Maksimal 500 karakter.');
                return;
            }

            const formData = new FormData();
            formData.append('receiver_id', receiverId);
            if (message) formData.append('message', message);
            if (file) formData.append('attachment', file);

            // Reset komponen UI form
            chatInput.value = '';
            chatFile.value = '';
            filePreviewBar.classList.add('hidden');
            filePreviewBar.classList.remove('flex');
            filePreviewName.textContent = '';

            try {
                // 1. Kirim data ke Laravel backend untuk di-save ke DB
                const res = await fetch('/chat/send', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData,
                });
                const data = await res.json();

                // 2. Render di browser sendiri secara instan
                appendMessage(userId, data.message, data.attachment_url, data.attachment_name, data.created_at);

                // 3. Emit via socket.io membawa attachment_url asli agar realtime di layar lawan tanpa refresh
                socket.emit('send-message', { 
                    senderId: userId, 
                    receiverId: receiverId, 
                    message: data.message || '', 
                    fileUrl: data.attachment_url || null,
                    messageType: file ? 'file' : 'text',
                    created_at: data.created_at 
                });

            } catch (error) {
                console.error("Gagal mengirim pesan:", error);
            }
        });

        // Typing indicator
        let typingTimeout;
        chatInput.addEventListener('input', () => {
            socket.emit('typing', { senderId: userId, receiverId });
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => socket.emit('stop-typing', { senderId: userId, receiverId }), 1000);
        });

        window.addEventListener('beforeunload', () => {
            socket.emit('leave-chat-room', { userId, partnerId: receiverId });
        });
    </script>
    @endpush
@endif
</x-app-layout>