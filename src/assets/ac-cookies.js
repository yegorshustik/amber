(function () {
  var STORAGE_KEY = 'ac_cookies_consent';

  function init() {
    var consent = localStorage.getItem(STORAGE_KEY);
    if (consent) return; // Consent already given or declined

    // Create banner element
    var banner = document.createElement('div');
    banner.className = 'ac-cookies';
    banner.id = 'ac-cookie-banner';
    banner.innerHTML =
      '<div class="ac-cookies__inner">' +
        '<p class="ac-cookies__text">We use cookies to analyze traffic and improve your experience.</p>' +
        '<div class="ac-cookies__btns">' +
          '<button class="ac-btn ac-btn--primary" id="cookies-accept" type="button">Accept</button>' +
          '<button class="ac-btn ac-btn--secondary-dark" id="cookies-decline" type="button">Decline</button>' +
        '</div>' +
      '</div>';

    document.body.appendChild(banner);

    // Trigger slide-up animation with a 1-second delay
    setTimeout(function () {
      banner.classList.add('is-visible');
    }, 1000);

    var acceptBtn = document.getElementById('cookies-accept');
    var declineBtn = document.getElementById('cookies-decline');

    if (acceptBtn) {
      acceptBtn.addEventListener('click', function () {
        localStorage.setItem(STORAGE_KEY, 'accepted');
        hide();
      });
    }

    if (declineBtn) {
      declineBtn.addEventListener('click', function () {
        localStorage.setItem(STORAGE_KEY, 'declined');
        hide();
      });
    }

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
