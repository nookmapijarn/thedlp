importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Initialize the Firebase app in the service worker
const firebaseConfig = {
    apiKey: "tRy6n8XtiIXsfQF_AtWgMg02NH4MaTkIPKiZ6LGXrK0",
    authDomain: "olis-b1bc2.firebaseapp.com",
    projectId: "olis-b1bc2",
    storageBucket: "olis-b1bc2.appspot.com",
    messagingSenderId: "975237631927",
    appId: "1:975237631927:web:2237efa9d4e9a6099b2232"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    
    const notificationTitle = payload.notification.title || 'แจ้งเตือนจากระบบ OLIS';
    const notificationOptions = {
        body: payload.notification.body || 'มีข้อความอัปเดตใหม่ถึงคุณ',
        icon: '/storage/logo.png',
        badge: '/storage/logo.png',
        data: payload.data || {}
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
