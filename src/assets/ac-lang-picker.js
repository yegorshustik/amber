/* Amber Council — Language Picker
   Toggle dropdown, select language, update label, close on outside click. */
(function () {
  function init() {
    var pickers = document.querySelectorAll('.ac-lang-picker');
    pickers.forEach(function (picker) {
      var toggle = picker.querySelector('.ac-lang-picker__toggle');
      var menu = picker.querySelector('.ac-lang-picker__menu');
      var currentLabel = picker.querySelector('.ac-lang-picker__current');
      var items = menu.querySelectorAll('li');

      // Toggle menu
      toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = picker.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });

      // Select a language
      items.forEach(function (item) {
        item.addEventListener('click', function () {
          // Update aria-selected
          items.forEach(function (li) { li.setAttribute('aria-selected', 'false'); });
          item.setAttribute('aria-selected', 'true');

          // Update the visible label
          currentLabel.textContent = item.textContent.replace('✓ ', '');

          // Close the menu
          picker.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');

          // In the future, this is where navigation to the localized version would happen:
          // var lang = item.getAttribute('data-lang');
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
