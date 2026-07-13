<!-- Global Page Loader Overlay -->
<div id="global-page-loader" class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-slate-950/70 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300 ease-out">
    <div class="relative flex flex-col items-center justify-center p-8 rounded-[2.5rem] bg-white/10 dark:bg-slate-900/40 border border-white/10 shadow-2xl backdrop-blur-xl scale-90 transition-all duration-300 ease-out animate-in fade-in" id="global-page-loader-card">
        <!-- Glowing Double Ring Spinner -->
        <div class="relative w-20 h-20">
            <div class="absolute inset-0 rounded-full border-4 border-purple-500/10"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-purple-600 border-r-purple-600 animate-spin"></div>
            <div class="absolute inset-2 rounded-full border-4 border-indigo-500/5"></div>
            <div class="absolute inset-2 rounded-full border-4 border-b-indigo-500 border-l-indigo-500 animate-spin-reverse" style="animation-duration: 1s;"></div>
            <!-- School Logo in the Center -->
            <div class="absolute inset-4.5 rounded-2xl bg-white dark:bg-slate-950 flex items-center justify-center shadow-inner overflow-hidden">
                <img src="/storage/olislogo.png" alt="OLIS Logo" class="w-9.5 h-9.5 object-contain">
            </div>
        </div>
        
        <h3 class="mt-6 text-sm font-black text-white tracking-wider uppercase">กำลังโหลดข้อมูล...</h3>
        <p class="mt-1 text-[10px] text-purple-200 dark:text-purple-300/80 font-bold tracking-wider">โปรดรอสักครู่ ระบบกำลังเปลี่ยนหน้าเพจ</p>
    </div>
</div>

<style>
    @keyframes spin-reverse {
        0% { transform: rotate(360deg); }
        100% { transform: rotate(0deg); }
    }
    .animate-spin-reverse {
        animation: spin-reverse 1.5s linear infinite;
    }
    .inset-4.5 {
        top: 1.125rem;
        right: 1.125rem;
        bottom: 1.125rem;
        left: 1.125rem;
    }
</style>

<script>
    (function () {
        // Run as self-executing function to avoid polluting global namespace
        document.addEventListener("DOMContentLoaded", function () {
            const loader = document.getElementById('global-page-loader');
            const loaderCard = document.getElementById('global-page-loader-card');

            function showLoader() {
                if (loader) {
                    loader.classList.remove('opacity-0', 'pointer-events-none');
                    loader.classList.add('opacity-100', 'pointer-events-auto');
                    if (loaderCard) {
                        loaderCard.classList.remove('scale-90');
                        loaderCard.classList.add('scale-100');
                    }
                }
            }

            function hideLoader() {
                if (loader) {
                    loader.classList.remove('opacity-100', 'pointer-events-auto');
                    loader.classList.add('opacity-0', 'pointer-events-none');
                    if (loaderCard) {
                        loaderCard.classList.remove('scale-100');
                        loaderCard.classList.add('scale-90');
                    }
                }
            }

            window.showPageLoader = showLoader;
            window.hidePageLoader = hideLoader;

            // Hide loader when page is loaded or restored from cache (history back/forward)
            window.addEventListener('pageshow', (event) => {
                hideLoader();
            });

            // Show loader on page transition (beforeunload)
            window.addEventListener('beforeunload', () => {
                showLoader();
            });

            // Intercept standard anchor link clicks
            document.addEventListener('click', (e) => {
                const anchor = e.target.closest('a');
                if (!anchor) return;

                const href = anchor.getAttribute('href');
                const target = anchor.getAttribute('target');

                // Skip if:
                // - Clicked with modifier key (Ctrl, Cmd, Shift, Alt)
                // - Target is new tab/window (_blank)
                // - Href is empty, anchor hash link, or tel/mailto
                // - Href is javascript action
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                if (target && target === '_blank') return;
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;

                // Only show loader if navigating to a different page in the same origin
                try {
                    const url = new URL(anchor.href, window.location.href);
                    if (url.origin === window.location.origin && url.href !== window.location.href) {
                        showLoader();
                    }
                } catch (e) {
                    // Invalid URL structure, do nothing
                }
            });

            // Intercept form submissions (e.g. login form, reply form)
            document.addEventListener('submit', (e) => {
                if (e.defaultPrevented) return; // SKIP if submit is intercepted by JS / AJAX
                const form = e.target;
                if (form && !form.getAttribute('target')) {
                    showLoader();
                }
            });
        });
    })();
</script>
