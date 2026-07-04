/* Amber Council — Language Picker
   Toggle dropdown, select language, update label, close on outside click.
   Syncs selection across all pickers on the page. */
(function () {
  function init() {
    var allPickers = document.querySelectorAll('.ac-lang-picker');

    allPickers.forEach(function (picker) {
      var toggle = picker.querySelector('.ac-lang-picker__toggle');
      var menu = picker.querySelector('.ac-lang-picker__menu');
      var currentLabel = picker.querySelector('.ac-lang-picker__current');
      var items = menu.querySelectorAll('li');

      // Toggle menu
      toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        // Close other open pickers first
        allPickers.forEach(function (other) {
          if (other !== picker) {
            other.classList.remove('is-open');
            other.querySelector('.ac-lang-picker__toggle').setAttribute('aria-expanded', 'false');
          }
        });
        var isOpen = picker.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });

      // Select a language
      items.forEach(function (item) {
        item.addEventListener('click', function () {
          var lang = item.getAttribute('data-lang');
          var langName = item.textContent.replace('✓ ', '');

          // Update all pickers on the page to stay in sync
          allPickers.forEach(function (p) {
            var pLabel = p.querySelector('.ac-lang-picker__current');
            var pItems = p.querySelectorAll('.ac-lang-picker__menu li');
            pItems.forEach(function (li) {
              li.setAttribute('aria-selected', li.getAttribute('data-lang') === lang ? 'true' : 'false');
            });
            if (pLabel) pLabel.textContent = langName;
            p.classList.remove('is-open');
            p.querySelector('.ac-lang-picker__toggle').setAttribute('aria-expanded', 'false');
          });

          // In the future, this is where navigation to the localized version would happen:
          // window.location.href = '/' + lang + '/';
        });
      });

      // Close on outside click
      document.addEventListener('click', function (e) {
        if (!picker.contains(e.target)) {
          picker.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
        }
      });

      // Close on Escape
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && picker.classList.contains('is-open')) {
          picker.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
          toggle.focus();
        }
      });
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
