document.addEventListener('DOMContentLoaded', () => {
    const marketingMenuBtn = document.getElementById('foliioo-marketing-mobile-btn');
    const marketingMenu = document.getElementById('foliioo-marketing-mobile-menu');
    if (marketingMenuBtn && marketingMenu) {
        marketingMenuBtn.addEventListener('click', () => {
            const isExpanded = marketingMenuBtn.getAttribute('aria-expanded') === 'true';
            marketingMenuBtn.setAttribute('aria-expanded', !isExpanded);
            marketingMenu.classList.toggle('hidden');
        });
    }

    const dashboardToggleBtn = document.getElementById('foliioo-dashboard-sidebar-toggle');
    const dashboardSidebar = document.getElementById('foliioo-dashboard-sidebar');
    const dashboardBackdrop = document.getElementById('foliioo-dashboard-backdrop');
    const collapseBtn = document.getElementById('foliioo-sidebar-collapse-btn');
    const collapseIcon = document.getElementById('foliioo-collapse-icon');
    const mainCanvas = document.getElementById('foliioo-dashboard-canvas');
    const topHeader = document.getElementById('foliioo-top-header');

    function toggleMobileDashboardSidebar() {
        if (!dashboardSidebar) return;
        const isClosed = dashboardSidebar.classList.contains('-translate-x-[110%]');
        dashboardSidebar.classList.toggle('-translate-x-[110%]', !isClosed);
        dashboardSidebar.classList.toggle('translate-x-0', isClosed);
        if (dashboardBackdrop) {
            dashboardBackdrop.classList.toggle('hidden', !isClosed);
        }
    }

    if (dashboardToggleBtn) dashboardToggleBtn.addEventListener('click', toggleMobileDashboardSidebar);
    if (dashboardBackdrop) dashboardBackdrop.addEventListener('click', toggleMobileDashboardSidebar);

    if (collapseBtn && dashboardSidebar) {
        collapseBtn.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                toggleMobileDashboardSidebar();
                return;
            }
            const isShrunk = dashboardSidebar.classList.contains('sidebar-shrunk');
            const texts = dashboardSidebar.querySelectorAll('.sidebar-text');
            const dropdown = dashboardSidebar.querySelector('.sidebar-dropdown');
            const bottomCard = dashboardSidebar.querySelector('.sidebar-bottom-card');
            const sidebarLogo = document.getElementById('foliioo-sidebar-logo');

            if (isShrunk) {
                dashboardSidebar.classList.remove('sidebar-shrunk', 'lg:w-20');
                dashboardSidebar.classList.add('lg:w-64');
                texts.forEach(t => t.classList.remove('hidden'));
                if (dropdown) dropdown.classList.remove('hidden');
                if (bottomCard) bottomCard.classList.remove('hidden');
                if (sidebarLogo) sidebarLogo.classList.remove('hidden');
                if (mainCanvas) {
                    mainCanvas.classList.remove('lg:pl-28');
                    mainCanvas.classList.add('lg:pl-72');
                }
                if (topHeader) {
                    topHeader.classList.remove('lg:pl-28');
                    topHeader.classList.add('lg:pl-72');
                }
                if (collapseIcon) collapseIcon.setAttribute('data-lucide', 'panel-left-close');
            } else {
                dashboardSidebar.classList.add('sidebar-shrunk', 'lg:w-20');
                dashboardSidebar.classList.remove('lg:w-64');
                texts.forEach(t => t.classList.add('hidden'));
                if (dropdown) dropdown.classList.add('hidden');
                if (bottomCard) bottomCard.classList.add('hidden');
                if (sidebarLogo) sidebarLogo.classList.add('hidden');
                if (mainCanvas) {
                    mainCanvas.classList.remove('lg:pl-72');
                    mainCanvas.classList.add('lg:pl-28');
                }
                if (topHeader) {
                    topHeader.classList.remove('lg:pl-72');
                    topHeader.classList.add('lg:pl-28');
                }
                if (collapseIcon) collapseIcon.setAttribute('data-lucide', 'panel-left-open');
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    }

    const profileBtn = document.getElementById('foliioo-user-menu-btn');
    const profileDropdown = document.getElementById('foliioo-user-dropdown');
    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target) && !profileBtn.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    }

    const postponedBtn = document.getElementById('foliioo-postponed-btn');
    const postponedPopover = document.getElementById('foliioo-postponed-popover');
    if (postponedBtn && postponedPopover) {
        postponedBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            postponedPopover.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!postponedPopover.contains(e.target) && !postponedBtn.contains(e.target)) {
                postponedPopover.classList.add('hidden');
            }
        });
    }

    const eventMobileBtn = document.getElementById('foliioo-event-mobile-btn');
    const eventMobileDropdown = document.getElementById('foliioo-event-mobile-dropdown');
    if (eventMobileBtn && eventMobileDropdown) {
        eventMobileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            eventMobileDropdown.classList.toggle('hidden');
        });
        eventMobileDropdown.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                eventMobileDropdown.classList.add('hidden');
            });
        });
        document.addEventListener('click', (e) => {
            if (!eventMobileDropdown.contains(e.target) && !eventMobileBtn.contains(e.target)) {
                eventMobileDropdown.classList.add('hidden');
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
                profileDropdown.classList.add('hidden');
            }
            if (dashboardSidebar && dashboardSidebar.classList.contains('translate-x-0')) {
                toggleMobileDashboardSidebar();
            }
            if (postponedPopover && !postponedPopover.classList.contains('hidden')) {
                postponedPopover.classList.add('hidden');
            }
            if (eventMobileDropdown && !eventMobileDropdown.classList.contains('hidden')) {
                eventMobileDropdown.classList.add('hidden');
            }
        }
    });

    const eventNavLinks = document.querySelectorAll('.foliioo-event-nav-link');
    if (eventNavLinks.length > 0) {
        const sections = [];
        eventNavLinks.forEach(link => {
            const targetId = link.getAttribute('href');
            if (targetId && targetId.startsWith('#')) {
                const sec = document.querySelector(targetId);
                if (sec) sections.push({ id: targetId, el: sec, link: link });
            }
        });

        window.addEventListener('scroll', () => {
            const scrollPos = window.scrollY + 100;
            sections.forEach(({ el, link }) => {
                const top = el.offsetTop;
                const height = el.offsetHeight;
                if (scrollPos >= top && scrollPos < top + height) {
                    eventNavLinks.forEach(l => l.classList.remove('text-[#320E3B]', 'font-bold'));
                    link.classList.add('text-[#320E3B]', 'font-bold');
                }
            });
        }, { passive: true });
    }

    // Spec: Pre-registration event drafts stored locally before account creation
    window.FoliiooDraft = {
        STORAGE_KEY: 'foliioo_pending_draft',

        saveDraft(draftData) {
            try {
                localStorage.setItem(this.STORAGE_KEY, JSON.stringify({
                    ...draftData,
                    updated_at: new Date().toISOString()
                }));
                this.updateDraftIndicator();
                return true;
            } catch (e) {
                return false;
            }
        },

        getDraft() {
            try {
                const data = localStorage.getItem(this.STORAGE_KEY);
                return data ? JSON.parse(data) : null;
            } catch (e) {
                return null;
            }
        },

        clearDraft() {
            localStorage.removeItem(this.STORAGE_KEY);
            this.updateDraftIndicator();
        },

        updateDraftIndicator() {
            const indicator = document.getElementById('foliioo-resume-draft-badge');
            if (!indicator) return;
            const draft = this.getDraft();
            if (draft && draft.title) {
                indicator.classList.remove('hidden');
                indicator.textContent = `Resume Draft: ${draft.title.substring(0, 18)}...`;
            } else {
                indicator.classList.add('hidden');
            }
        }
    };

    window.FoliiooDraft.updateDraftIndicator();
});
