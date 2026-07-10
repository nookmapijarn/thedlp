<!-- PWA Interactive Install Prompt Card -->
<div id="pwa-install-prompt" class="fixed bottom-6 left-6 z-[999] max-w-md w-[calc(100%-3rem)] sm:w-96 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl p-5 transform -translate-y-10 opacity-0 pointer-events-none transition-all duration-500 ease-out flex gap-4">
    <!-- Glowing Animated Download Icon -->
    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center relative">
        <span class="absolute inline-flex h-3 w-3 rounded-full bg-emerald-500 opacity-75 animate-ping -top-1 -right-1"></span>
        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
    </div>
    <!-- Prompt Text & Action Buttons -->
    <div class="flex-grow space-y-1.5">
        <h4 class="text-sm font-black text-slate-900 dark:text-white">ติดตั้งแอปพลิเคชัน OLIS</h4>
        <p class="text-xs text-slate-500 dark:text-slate-400 font-bold leading-relaxed">ติดตั้งแอปฯ ลงบนหน้าจอหลักมือถือหรือคอมฯ เพื่อการเข้าเรียนออนไลน์ที่รวดเร็ว และรับการแจ้งเตือนปัญหาทันที</p>
        <div class="flex gap-2 pt-1.5">
            <button id="pwa-prompt-btn-install" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-[11px] rounded-xl transition-all shadow-md active:scale-95">
                ติดตั้งเลย
            </button>
            <button id="pwa-prompt-btn-dismiss" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-extrabold text-[11px] rounded-xl transition-all active:scale-95">
                ไว้ทีหลัง
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let deferredPrompt;
        const promptEl = document.getElementById('pwa-install-prompt');
        const btnInstall = document.getElementById('pwa-prompt-btn-install');
        const btnDismiss = document.getElementById('pwa-prompt-btn-dismiss');

        // Check if the app is already installed or running in standalone mode
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

        if (isStandalone) {
            console.log('[PWA] Already running in standalone mode. Skipping install prompt.');
            return;
        }

        // Listen for the browser PWA installation trigger
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent standard browser direct prompt bar
            e.preventDefault();
            
            // Store event details
            deferredPrompt = e;
            
            // Show our custom premium invite card after a brief delay
            setTimeout(() => {
                showPwaPrompt();
            }, 3000);
        });

        if (btnInstall) {
            btnInstall.onclick = async function () {
                if (!deferredPrompt) return;
                
                // Hide custom invitation card
                hidePwaPrompt();
                
                // Open native browser installation interface
                deferredPrompt.prompt();
                
                // Wait for choice
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`[PWA] Install choice outcome: ${outcome}`);
                
                deferredPrompt = null;
            };
        }

        if (btnDismiss) {
            btnDismiss.onclick = function () {
                hidePwaPrompt();
                
                // Store dismiss state in sessionStorage to avoid annoying the user on every redirect
                sessionStorage.setItem('pwa-prompt-dismissed', 'true');
            };
        }

        function showPwaPrompt() {
            // Do not show if dismissed in this session
            if (sessionStorage.getItem('pwa-prompt-dismissed') === 'true') {
                return;
            }
            if (promptEl) {
                promptEl.classList.remove('-translate-y-10', 'opacity-0', 'pointer-events-none');
                promptEl.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
            }
        }

        function hidePwaPrompt() {
            if (promptEl) {
                promptEl.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
                promptEl.classList.add('-translate-y-10', 'opacity-0', 'pointer-events-none');
            }
        }

        // Detect successful PWA installation event
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] Application successfully installed.');
            hidePwaPrompt();
            deferredPrompt = null;
        });
    });
</script>
