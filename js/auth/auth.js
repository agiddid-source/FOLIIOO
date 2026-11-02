/* ========================================
   FOLIIOO — MOCK AUTH (frontend prototype only)
   Accounts live in this browser (localStorage). This simulates sign-up and
   login for the prototype. It is NOT real authentication and must be replaced
   by a backend before launch.
======================================== */
(function () {
    'use strict';

    const ACCOUNTS_KEY = 'foliiooCreatorAccounts';
    const LEGACY_ACCOUNT_KEY = 'foliiooCreatorAccount';
    const SESSION_KEY = 'foliiooCreatorSession';

    // Where to send people after login/register when no "next" page is given.
    // Switch to 'dashboard.php' once the dashboard (Module 5) exists.
    const DEFAULT_REDIRECT = 'index.php';

    // Only same-folder .php pages may be used as a "next" destination.
    const SAFE_NEXT = /^[a-z0-9-]+\.php(\?[A-Za-z0-9_=&%.\-]*)?$/i;

    /* ---------- Storage helpers ---------- */
    function readJSON(storage, key, fallback) {
        try {
            const raw = storage.getItem(key);
            return raw ? JSON.parse(raw) : fallback;
        } catch (error) {
            return fallback;
        }
    }

    function writeJSON(storage, key, value) {
        try {
            storage.setItem(key, JSON.stringify(value));
            return true;
        } catch (error) {
            return false;
        }
    }

    function readAccounts() {
        const accounts = readJSON(localStorage, ACCOUNTS_KEY, []);
        const list = Array.isArray(accounts) ? accounts : [];

        // Migrate an account created by the earlier single-account version.
        const legacy = readJSON(sessionStorage, LEGACY_ACCOUNT_KEY, null)
            || readJSON(localStorage, LEGACY_ACCOUNT_KEY, null);

        if (legacy && legacy.email && !list.some(a => a.email === legacy.email)) {
            list.push(legacy);
            writeJSON(localStorage, ACCOUNTS_KEY, list);
        }

        return list;
    }

    function findAccount(email) {
        const target = String(email || '').trim().toLowerCase();

        return readAccounts().find(account => account.email === target) || null;
    }

    /* ---------- Hashing (prototype only, not production security) ---------- */
    function canHash() {
        return !!(window.crypto && window.crypto.subtle && window.isSecureContext);
    }

    function randomSalt() {
        const bytes = new Uint8Array(16);

        window.crypto.getRandomValues(bytes);

        return Array.from(bytes, b => b.toString(16).padStart(2, '0')).join('');
    }

    async function hashPassword(password, salt) {
        // No salt = account created by the earlier version.
        const input = salt ? `${salt}:${password}` : password;
        const digest = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(input));

        return Array.from(new Uint8Array(digest), b => b.toString(16).padStart(2, '0')).join('');
    }

    const HASH_UNAVAILABLE =
        'Secure hashing needs HTTPS or localhost. Open the site from http://localhost and try again.';

    /* ---------- Account actions ---------- */
    async function createAccount({ fullName, email, password }) {
        if (!canHash()) throw new Error(HASH_UNAVAILABLE);

        const salt = randomSalt();
        const account = {
            fullName,
            email: email.trim().toLowerCase(),
            salt,
            passwordHash: await hashPassword(password, salt),
            createdAt: new Date().toISOString()
        };

        const accounts = readAccounts();

        accounts.push(account);

        if (!writeJSON(localStorage, ACCOUNTS_KEY, accounts)) {
            throw new Error('Browser storage is unavailable. Please try again.');
        }

        return account;
    }

    async function verifyLogin(email, password) {
        if (!canHash()) throw new Error(HASH_UNAVAILABLE);

        if (readAccounts().length === 0) {
            throw new Error('No creator account was found in this browser. Please register first.');
        }

        const account = findAccount(email);
        const generic = 'Incorrect email or password. Please try again.';

        if (!account) throw new Error(generic);

        const hash = await hashPassword(password, account.salt);

        if (hash !== account.passwordHash) throw new Error(generic);

        return account;
    }

    async function resetPassword(email, newPassword) {
        if (!canHash()) throw new Error(HASH_UNAVAILABLE);

        const accounts = readAccounts();
        const index = accounts.findIndex(a => a.email === email.trim().toLowerCase());

        if (index === -1) throw new Error('No account found.');

        const salt = randomSalt();

        accounts[index] = {
            ...accounts[index],
            salt,
            passwordHash: await hashPassword(newPassword, salt),
            passwordUpdatedAt: new Date().toISOString()
        };

        if (!writeJSON(localStorage, ACCOUNTS_KEY, accounts)) {
            throw new Error('Browser storage is unavailable. Please try again.');
        }
    }

    /* ---------- Session ---------- */
    function startSession(account, remember) {
        const session = {
            fullName: account.fullName,
            email: account.email,
            signedInAt: new Date().toISOString()
        };

        // One active session only.
        sessionStorage.removeItem(SESSION_KEY);
        localStorage.removeItem(SESSION_KEY);

        writeJSON(remember ? localStorage : sessionStorage, SESSION_KEY, session);

        return session;
    }

    function getSession() {
        return readJSON(sessionStorage, SESSION_KEY, null)
            || readJSON(localStorage, SESSION_KEY, null);
    }

    function isSignedIn() {
        return !!getSession();
    }

    function signOut() {
        sessionStorage.removeItem(SESSION_KEY);
        localStorage.removeItem(SESSION_KEY);
    }

    /* ---------- Journey: remember where the visitor was going ---------- */
    function getNext() {
        const next = new URLSearchParams(window.location.search).get('next');

        return next && SAFE_NEXT.test(next) ? next : '';
    }

    function afterAuthUrl() {
        return getNext() || DEFAULT_REDIRECT;
    }

    function withNext(url, next) {
        return next ? `${url}?next=${encodeURIComponent(next)}` : url;
    }

    /* ---------- Shared UI behaviour ---------- */
    // The only place password toggles are bound. Do not bind them again in
    // register.js, login.js or forgot-password.js (two handlers cancel out).
    function bindPasswordToggles() {
        document.querySelectorAll('[data-password-toggle]').forEach(button => {
            const field = document.getElementById(button.dataset.passwordToggle);
            const icon = button.querySelector('i');

            if (!field || !icon) return;

            button.setAttribute('aria-pressed', 'false');

            button.addEventListener('click', event => {
                event.preventDefault();

                const show = field.type === 'password';

                field.type = show ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !show);
                icon.classList.toggle('fa-eye-slash', show);
                button.setAttribute('aria-pressed', String(show));
                button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                field.focus();
            });
        });
    }

    function carryNextOnAuthLinks() {
        const next = getNext();

        if (!next) return;

        document
            .querySelectorAll('.auth-page a[href="login.php"], .auth-page a[href="register.php"]')
            .forEach(link => {
                link.href = withNext(link.getAttribute('href'), next);
            });
    }

    function updateHeader() {
        const session = getSession();

        if (!session) return;

        const firstName = (session.fullName || 'there').split(' ')[0];

        function makeLogout(className) {
            const button = document.createElement('button');

            button.type = 'button';
            button.className = className;
            button.textContent = 'Log out';
            button.addEventListener('click', () => {
                signOut();
                window.location.href = 'index.php';
            });

            return button;
        }

        const desktopLogin = document.querySelector('.site-nav-actions .site-login-link');

        if (desktopLogin) {
            const greeting = document.createElement('span');

            greeting.className = 'site-user-greeting';
            greeting.textContent = `Hi, ${firstName}`;

            desktopLogin.replaceWith(greeting, makeLogout('site-login-link site-logout-button'));
        }

        const mobileLogin = document.querySelector('.mobile-menu-inner a[href="login.php"]');

        if (mobileLogin) {
            mobileLogin.replaceWith(makeLogout('mobile-menu-link site-logout-button'));
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        bindPasswordToggles();
        carryNextOnAuthLinks();
        updateHeader();
    });

    window.FoliiooAuth = {
        canHash,
        createAccount,
        verifyLogin,
        resetPassword,
        findAccount,
        startSession,
        getSession,
        isSignedIn,
        signOut,
        getNext,
        afterAuthUrl,
        withNext
    };
})();