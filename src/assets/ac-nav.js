/* ---- Mobile nav burger ---- */
(function () {
  document.addEventListener('click', function(e) {
    var b = e.target.closest('.ac-burger');
    var n = document.getElementById('site-nav');
    
    if (b && n) {
      var open = n.classList.toggle('is-open');
      b.setAttribute('aria-expanded', open ? 'true' : 'false');
    } else if (n && n.classList.contains('is-open')) {
      // If clicking inside a link in the nav, close it
      if (e.target.closest('a')) {
        n.classList.remove('is-open');
        var burger = document.querySelector('.ac-burger');
        if (burger) burger.setAttribute('aria-expanded', 'false');
      }
    }
  });
})();
