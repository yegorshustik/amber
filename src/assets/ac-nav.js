/* ---- Mobile nav burger ---- */
(function () {
  var b = document.querySelector('.ac-burger');
  var n = document.getElementById('site-nav');
  if (!b || !n) return;
  b.addEventListener('click', function () {
    var open = n.classList.toggle('is-open');
    b.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  n.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      n.classList.remove('is-open');
      b.setAttribute('aria-expanded', 'false');
    });
  });
})();
