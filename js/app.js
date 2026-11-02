
/* ========================================
   FOLIIOO — SHARED INTERACTIONS
======================================== */

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (!menuToggle || !mobileMenu) return;

    const menuIcon = menuToggle.querySelector('i');

    function closeMobileMenu() {
        mobileMenu.hidden = true;
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Open navigation menu');

        if (menuIcon) {
            menuIcon.classList.remove('fa-xmark');
            menuIcon.classList.add('fa-bars');
        }
    }

    function toggleMobileMenu() {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';

        mobileMenu.hidden = isOpen;
        menuToggle.setAttribute('aria-expanded', String(!isOpen));
        menuToggle.setAttribute(
            'aria-label',
            isOpen ? 'Open navigation menu' : 'Close navigation menu'
        );

        if (menuIcon) {
            menuIcon.classList.toggle('fa-bars', isOpen);
            menuIcon.classList.toggle('fa-xmark', !isOpen);
        }
    }

    menuToggle.addEventListener('click', toggleMobileMenu);

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeMobileMenu);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMobileMenu();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 800) {
            closeMobileMenu();
        }
    });
});



    /* ========================================
       TEMPLATE GALLERY
    ======================================== */

    const templateGrid = document.getElementById('templateGrid');

    if (templateGrid) {
        const templateCards = [
            ...templateGrid.querySelectorAll('[data-template]')
        ];

        const filterButtons = document.querySelectorAll('[data-filter]');
        const templateCount = document.getElementById('templateCount');
        const emptyState = document.getElementById('galleryEmptyState');

        function filterTemplates(filter) {
            let visibleCount = 0;

            templateCards.forEach((card) => {
                const matches =
                    filter === 'all' ||
                    card.dataset.template === filter;

                card.hidden = !matches;

                if (matches) visibleCount++;
            });

            filterButtons.forEach((button) => {
                const active = button.dataset.filter === filter;

                button.classList.toggle('active', active);
                button.setAttribute('aria-pressed', String(active));
            });

            if (templateCount) {
                templateCount.textContent =
                    `Showing ${visibleCount} ${visibleCount === 1 ? 'style' : 'styles'}`;
            }

            if (emptyState) {
                emptyState.hidden = visibleCount !== 0;
            }
        }

        filterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                filterTemplates(button.dataset.filter);
            });
        });

        /* Preview modal */
        const templateDetails = {
            elegant: {
                title: 'Elegant',
                eyebrow: 'TIMELESS & REFINED',
                description:
                    'A graceful style with warm neutrals and refined details, suited to weddings, anniversaries and formal celebrations.',
                image:
                    'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1000&q=85',
                imageAlt: 'Elegant wedding celebration'
            },

            modern: {
                title: 'Modern',
                eyebrow: 'CLEAN & CONTEMPORARY',
                description:
                    'A confident, contemporary direction with clean compositions for birthdays, graduations and corporate events.',
                image:
                    'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1000&q=85',
                imageAlt: 'Contemporary event setting'
            },

            floral: {
                title: 'Floral',
                eyebrow: 'SOFT & ROMANTIC',
                description:
                    'A softer aesthetic with romantic details and gentle tones for weddings, birthdays and meaningful gatherings.',
                image:
                    'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1000&q=85',
                imageAlt: 'Floral celebration tablescape'
            }
        };

        const modal = document.getElementById('templateModal');
        const modalTitle = document.getElementById('templateModalTitle');
        const modalEyebrow = document.getElementById('templateModalEyebrow');
        const modalDescription = document.getElementById('templateModalDescription');
        const modalImage = document.getElementById('templateModalImage');
        const modalUse = document.getElementById('templateModalUse');

        let lastFocusedElement = null;

        function closeTemplateModal() {
            if (!modal || modal.hidden) return;

            modal.hidden = true;
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';

            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
        }

        function openTemplateModal(templateKey, trigger) {
            const details = templateDetails[templateKey];

            if (!modal || !details) return;

            lastFocusedElement = trigger;

            modalTitle.textContent = details.title;
            modalEyebrow.textContent = details.eyebrow;
            modalDescription.textContent = details.description;
            modalImage.src = details.image;
            modalImage.alt = details.imageAlt;
            modalUse.href =
                `create-event.php?template=${encodeURIComponent(templateKey)}`;

            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            modal.querySelector('.template-modal-close').focus();
        }

        document.querySelectorAll('[data-preview]').forEach((button) => {
            button.addEventListener('click', () => {
                openTemplateModal(button.dataset.preview, button);
            });
        });

        if (modal) {
            modal.querySelectorAll('[data-close-modal]').forEach((element) => {
                element.addEventListener('click', closeTemplateModal);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeTemplateModal();
                }
            });
        }
    }

/* ========================================
   SCROLL REVEAL (respects reduced motion)
======================================== */
document.addEventListener('DOMContentLoaded', () => {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduce || !('IntersectionObserver' in window)) return;

    const targets = document.querySelectorAll(
        '.section-heading-wrap, .template-card, .step-card, .feature-item, .pricing-card, .final-cta'
    );

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12 });

    targets.forEach((el) => {
        el.classList.add('js-reveal');
        observer.observe(el);
    });
});
