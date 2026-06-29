(function () {
  var STORAGE_KEY = 'ac_cookies_consent';

  function init() {
    var consent;
    try {
      consent = localStorage.getItem(STORAGE_KEY);
    } catch (e) {
      consent = null;
    }
    if (consent) return; // Consent already given or declined

    // Create banner element
    var banner = document.createElement('div');
    banner.className = 'ac-notice';
    banner.id = 'ac-notice-banner';
    banner.innerHTML =
      '<div class="ac-notice__inner">' +
        '<p class="ac-notice__text">We use cookies to analyze traffic and improve your experience.</p>' +
        '<div class="ac-notice__btns">' +
          '<button class="ac-btn ac-btn--primary" id="notice-accept" type="button">Accept</button>' +
          '<button class="ac-btn ac-btn--secondary-dark" id="notice-decline" type="button">Decline</button>' +
        '</div>' +
      '</div>';

    document.body.appendChild(banner);

    // Trigger slide-up animation with a 1-second delay
    setTimeout(function () {
      banner.classList.add('is-visible');
    }, 1000);

    document.addEventListener('click', function(e) {
      if (!e.target || typeof e.target.closest !== 'function') return;
      if (e.target.closest('#notice-accept')) {
        try { localStorage.setItem(STORAGE_KEY, 'accepted'); } catch(e) {}
        hide();
      } else if (e.target.closest('#notice-decline')) {
        try { localStorage.setItem(STORAGE_KEY, 'declined'); } catch(e) {}
        hide();
      }
    });

    function hide() {
      banner.classList.remove('is-visible');
      // Wait for transition duration before removing from DOM
      setTimeout(function () {
        banner.remove();
      }, 400);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
