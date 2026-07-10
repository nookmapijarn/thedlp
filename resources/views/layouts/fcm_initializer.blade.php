@auth
    @if(config('services.firebase.messaging_sender_id') && config('services.firebase.vapid_key'))
        <!-- Firebase SDK (Compat Version) -->
        <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js"></script>

        <style>
            @keyframes swing {
                0%, 100% { transform: rotate(0deg); }
                20% { transform: rotate(15deg); }
                40% { transform: rotate(-10deg); }
                60% { transform: rotate(5deg); }
                80% { transform: rotate(-5deg); }
            }
            .animate-swing {
                animation: swing 2.5s ease-in-out infinite;
                transform-origin: top center;
            }
        </style>

        <!-- FCM Interactive Permission Prompt Overlay -->
        <div id="fcm-permission-prompt" class="fixed bottom-6 right-6 z-[999] max-w-md w-[calc(100%-3rem)] sm:w-96 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl p-5 transform translate-y-20 opacity-0 pointer-events-none transition-all duration-500 ease-out flex gap-4">
            <!-- Glowing Animated Bell Icon -->
            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center relative">
                <span class="absolute inline-flex h-3 w-3 rounded-full bg-indigo-500 opacity-75 animate-ping -top-1 -right-1"></span>
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 animate-swing" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>
            <!-- Prompt Text & Action Buttons -->
            <div class="flex-grow space-y-1.5">
                <h4 id="fcm-prompt-title" class="text-sm font-black text-slate-900 dark:text-white">เปิดรับแจ้งเตือนสิทธิ์การเรียน (OLIS)</h4>
                <p id="fcm-prompt-desc" class="text-xs text-slate-500 dark:text-slate-400 font-bold leading-relaxed">โปรดอนุญาตสิทธิ์การแจ้งเตือน เพื่อไม่ให้พลาดข้อความสำคัญและการประสานงานช่วยเหลือต่างๆ เด้งขึ้นหน้าจออุปกรณ์นี้</p>
                <div class="flex gap-2 pt-1.5">
                    <button id="fcm-prompt-btn-allow" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-[11px] rounded-xl transition-all shadow-md active:scale-95">
                        เปิดรับแจ้งเตือน
                    </button>
                    <button id="fcm-prompt-btn-dismiss" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-extrabold text-[11px] rounded-xl transition-all active:scale-95">
                        ไว้ทีหลัง
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Initialize Firebase
                const firebaseConfig = {
                    apiKey: "{{ config('services.firebase.api_key') }}",
                    authDomain: "{{ config('services.firebase.auth_domain') }}",
                    projectId: "{{ config('services.firebase.project_id') }}",
                    storageBucket: "{{ config('services.firebase.storage_bucket') }}",
                    messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
                    appId: "{{ config('services.firebase.app_id') }}"
                };

                firebase.initializeApp(firebaseConfig);
                const messaging = firebase.messaging();

                // Listen to foreground messages and show notification if page is open
                messaging.onMessage((payload) => {
                    console.log('[FCM] Received foreground message: ', payload);
                    if (Notification.permission === 'granted') {
                        const notificationTitle = payload.notification.title || 'ศูนย์รับแจ้งปัญหา (OLIS)';
                        const notificationOptions = {
                            body: payload.notification.body,
                            icon: '/storage/olislogo.png',
                            data: payload.data
                        };
                        const notification = new Notification(notificationTitle, notificationOptions);
                        
                        notification.onclick = function(event) {
                            event.preventDefault();
                            const redirectUrl = payload.data ? payload.data.url : '/';
                            window.location.href = redirectUrl;
                        };
                    }
                });

                // Register Service Worker
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/firebase-messaging-sw.js')
                        .then((registration) => {
                            console.log('[FCM] Service Worker registered with scope: ', registration.scope);

                            // Wait until the service worker is active and ready
                            navigator.serviceWorker.ready.then((activeRegistration) => {
                                console.log('[FCM] Service Worker is active and ready.');
                                checkAndShowFcmPrompt(messaging);
                            });
                        })
                        .catch((err) => {
                            console.error('[FCM] Service Worker registration failed: ', err);
                        });
                }

                function checkAndShowFcmPrompt(messaging) {
                    const promptEl = document.getElementById('fcm-permission-prompt');
                    const titleEl = document.getElementById('fcm-prompt-title');
                    const descEl = document.getElementById('fcm-prompt-desc');
                    const btnAllow = document.getElementById('fcm-prompt-btn-allow');
                    const btnDismiss = document.getElementById('fcm-prompt-btn-dismiss');

                    if (!promptEl) return;

                    const permission = Notification.permission;

                    if (permission === 'default') {
                        // User has not been asked yet - show beautiful invite prompt
                        promptEl.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                        promptEl.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

                        btnAllow.onclick = function () {
                            hidePrompt(promptEl);
                            requestFcmPermission(messaging);
                        };

                        btnDismiss.onclick = function () {
                            hidePrompt(promptEl);
                        };
                    } else if (permission === 'denied') {
                        // User has blocked notifications in browser - show instructions on how to enable
                        titleEl.textContent = 'กรุณาเปิดสิทธิ์รับแจ้งเตือน (OLIS)';
                        descEl.textContent = 'ตรวจพบว่าบราวเซอร์นี้ถูกระงับสิทธิ์แจ้งเตือน โปรดคลิกที่สัญลักษณ์แม่กุญแจ 🔒 ด้านข้างลิงก์ URL และตั้งค่า "การแจ้งเตือน" (Notifications) เป็น "อนุญาต" (Allow)';
                        btnAllow.textContent = 'รับทราบ';
                        btnDismiss.style.display = 'none';

                        promptEl.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                        promptEl.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

                        btnAllow.onclick = function () {
                            hidePrompt(promptEl);
                        };
                    } else if (permission === 'granted') {
                        // Already allowed, get token in background
                        requestFcmPermission(messaging);
                    }
                }

                function hidePrompt(promptEl) {
                    promptEl.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
                    promptEl.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                }

                function requestFcmPermission(messaging) {
                    Notification.requestPermission().then((permission) => {
                        if (permission === 'granted') {
                            console.log('[FCM] Notification permission granted.');
                            
                            // Retrieve Device Token
                            messaging.getToken({
                                vapidKey: "{{ config('services.firebase.vapid_key') }}"
                            }).then((currentToken) => {
                                if (currentToken) {
                                    console.log('[FCM] Token retrieved: ', currentToken);
                                    
                                    // Send token to our server
                                    sendTokenToServer(currentToken);
                                } else {
                                    console.warn('[FCM] No registration token available. Request permission to generate one.');
                                }
                            }).catch((err) => {
                                console.error('[FCM] An error occurred while retrieving token: ', err);
                            });
                        } else {
                            console.warn('[FCM] Unable to get permission to notify.');
                        }
                    });
                }

                function sendTokenToServer(token) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch("{{ route('fcm.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            token: token,
                            device_type: /Mobi|Android|iPhone/i.test(navigator.userAgent) ? 'mobile' : 'desktop'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('[FCM] Token successfully stored on server: ', data);
                    })
                    .catch((err) => {
                        console.error('[FCM] Error storing token on server: ', err);
                    });
                }
            });
        </script>
    @endif
@endauth
