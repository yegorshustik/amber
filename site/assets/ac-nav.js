(function () {
  document.addEventListener('click', function(e) {
    var n = document.getElementById('site-nav');
    
    // If clicking inside a link in the nav, close it
    if (n && n.classList.contains('is-open')) {
      if (e.target.closest('a')) {
        n.classList.remove('is-open');
        var burger = document.querySelector('.ac-burger');
        if (burger) burger.setAttribute('aria-expanded', 'false');
      }
    }
  });
})();
