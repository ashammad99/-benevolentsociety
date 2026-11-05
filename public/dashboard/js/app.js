/**
 * BSD Admin — Core JS
 * app.js
 *
 * Handles: sidebar toggle, language switching,
 * modal open/close, dropdown menus, nav active state.
 */

/*
   SIDEBAR
 */
const isMobile = () => window.innerWidth <= 768;

function initSidebar() {
  const toggle = document.getElementById('sidebarToggle');
  if (!toggle) return;

  toggle.addEventListener('click', () => {
    if (isMobile()) {
      document.body.classList.toggle('mobile-open');
    } else {
      const collapsed = document.body.classList.toggle('sidebar-collapsed');
      localStorage.setItem('bsd-sidebar', collapsed ? '1' : '0');
      // RTL margin fix
      adjustMainMargin();
    }
  });

  // Restore state
  if (localStorage.getItem('bsd-sidebar') === '1' && !isMobile()) {
    document.body.classList.add('sidebar-collapsed');
  }

  // Close on overlay click (mobile)
  document.addEventListener('click', (e) => {
    if (!isMobile()) return;
    if (!document.body.classList.contains('mobile-open')) return;
    const sidebar = document.getElementById('sidebar');
    if (sidebar && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
      document.body.classList.remove('mobile-open');
    }
  });

  window.addEventListener('resize', () => {
    if (!isMobile()) document.body.classList.remove('mobile-open');
  });
}

function adjustMainMargin() {
  const mw  = document.querySelector('.main-wrap');
  if (!mw) return;
  const rtl = document.documentElement.getAttribute('dir') === 'rtl';
  const w   = document.body.classList.contains('sidebar-collapsed')
    ? 'var(--sidebar-collapsed)' : 'var(--sidebar-w)';
  mw.style.marginLeft  = rtl ? '0' : w;
  mw.style.marginRight = rtl ? w   : '0';
}

/*
   LANGUAGE SWITCH
 */
function setLang(l) {
  const html = document.documentElement;
  html.setAttribute('dir',  l === 'ar' ? 'rtl' : 'ltr');
  html.setAttribute('lang', l);

  // Topbar buttons
  const tlEn = document.getElementById('tl-en');
  const tlAr = document.getElementById('tl-ar');
  if (tlEn) tlEn.classList.toggle('active', l === 'en');
  if (tlAr) tlAr.classList.toggle('active', l === 'ar');

  // Sidebar nav font
  document.querySelectorAll('.nav-item').forEach(el => {
    el.style.fontFamily = l === 'ar' ? 'var(--font-ar)' : 'var(--font-en)';
  });

  adjustMainMargin();
  localStorage.setItem('bsd-lang', l);
}

function initLang() {
  const l = localStorage.getItem('bsd-lang') || 'en';
  if (l !== 'en') setLang(l);
}

/*
   NAV ACTIVE STATE
 */
function navigate(el, labelEn, labelAr) {
  document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
  el.classList.add('active');

  const bc = document.getElementById('bcCurrent');
  if (bc) {
    bc.innerHTML = `<span class="en">${labelEn}</span><span class="ar">${labelAr}</span>`;
    const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
    bc.querySelectorAll('.en').forEach(s => s.style.display = isRtl ? 'none' : 'inline');
    bc.querySelectorAll('.ar').forEach(s => s.style.display = isRtl ? 'inline' : 'none');
  }
}

/*
   MODALS
 */
function openModal(id) {
  const overlay = document.getElementById(id);
  if (overlay) overlay.classList.add('open');
}

function closeModal(id) {
  const overlay = document.getElementById(id);
  if (overlay) overlay.classList.remove('open');
}

function initModals() {
  // Close on overlay click
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) overlay.classList.remove('open');
    });
  });

  // ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.open').forEach(o => o.classList.remove('open'));
      document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
    }
  });
}

/*
   DROPDOWN MENUS
 */
function initDropdowns() {
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-dropdown]');

    // Close all open dropdowns first
    document.querySelectorAll('.dropdown.open').forEach(d => {
      if (!d.contains(e.target)) d.classList.remove('open');
    });

    if (trigger) {
      const dropdown = trigger.closest('.dropdown');
      if (dropdown) dropdown.classList.toggle('open');
    }
  });
}

/*
   TOGGLE SWITCHES
 */
function initToggles() {
  document.querySelectorAll('.toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
      toggle.classList.toggle('on');
      const input = toggle.previousElementSibling;
      if (input && input.type === 'hidden') {
        input.value = toggle.classList.contains('on') ? '1' : '0';
      }
    });
  });
}

/*
   DELETE CONFIRM
 */
function confirmDelete(message, callback) {
  // Simple confirm — replace with modal in production
  const msg = document.documentElement.getAttribute('dir') === 'rtl'
    ? 'هل أنت متأكد أنك تريد الحذف؟'
    : (message || 'Are you sure you want to delete this item?');
  if (window.confirm(msg)) callback();
}

/*
   NOTIFICATIONS TOAST
 */
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `alert alert-${type}`;
  toast.style.cssText = `
    position:fixed; bottom:24px; right:24px; z-index:9999;
    max-width:340px; box-shadow:var(--shadow-lg);
    animation: fadeUp 0.3s ease both;
  `;
  toast.innerHTML = `
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <span></span>
  `;
  toast.querySelector('span').textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3500);
}

/*
   INIT
 */
document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initLang();
  initModals();
  initDropdowns();
  initToggles();
});

// Expose globally for inline onclick handlers
window.setLang    = setLang;
window.navigate   = navigate;
window.openModal  = openModal;
window.closeModal = closeModal;
window.confirmDelete = confirmDelete;
window.showToast  = showToast;
