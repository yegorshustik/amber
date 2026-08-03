(function () {
  function init() {
    var btn = document.getElementById('scroll-to-top');
    var header = document.querySelector('.ac-header');
    var lastScrollY = 0;
    var compactThreshold = 80; // px before compact kicks in

    window.addEventListener('scroll', function() {
      var currentScrollY = window.scrollY;

      // Scroll-to-top button visibility
      if (btn) {
        if (currentScrollY > 300) {
          btn.classList.add('is-visible');
        } else {
          btn.classList.remove('is-visible');
        }
      }

      // Header compact on scroll down, expand on scroll up
      if (header) {
        if (currentScrollY > compactThreshold && currentScrollY > lastScrollY) {
          // Scrolling DOWN past threshold → compact
          header.classList.add('is-compact');
        } else if (currentScrollY < lastScrollY) {
          // Scrolling UP → expand
          header.classList.remove('is-compact');
        }
        // Also expand when at the very top
        if (currentScrollY <= 10) {
          header.classList.remove('is-compact');
        }
      }

      lastScrollY = currentScrollY;
    }, { passive: true });

    if (btn) {
      btn.addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
