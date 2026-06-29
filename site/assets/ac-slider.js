/* Amber Council — testimonials slider behavior
   This script only handles interactions (scrolling, dots, arrows).
   The actual HTML content is rendered server-side for SEO. */
(function () {

  function initSlider(root) {
    var track = root.querySelector('.ac-slider__track');
    if (!track) return;
    var slides = Array.prototype.slice.call(track.children);
    var nav = root.querySelector('.ac-slider__nav');
    if (slides.length < 2) { if (nav) nav.style.display = 'none'; return; }

    var dotsWrap = root.querySelector('[data-dots]');
    var prevBtn = root.querySelector('[data-prev]');
    var nextBtn = root.querySelector('[data-next]');
    var dots = [];

    // Find sibling eyebrow header to inject counter
    var eyebrow = root.previousElementSibling;
    var counterEl = null;
    if (eyebrow && eyebrow.classList.contains('ac-eyebrow')) {
      counterEl = eyebrow.querySelector('.ac-eyebrow-counter');
      if (!counterEl) {
        counterEl = document.createElement('span');
        counterEl.className = 'ac-eyebrow-counter';
        eyebrow.appendChild(counterEl);
      }
    }

    if (dotsWrap) {
      slides.forEach(function (_, i) {
        var d = document.createElement('button');
        d.type = 'button';
        d.className = 'ac-slider__dot';
        d.setAttribute('aria-label', 'Go to testimonial ' + (i + 1));
        d.addEventListener('click', function () { scrollToIndex(i); });
        dotsWrap.appendChild(d);
        dots.push(d);
      });
    }

    function currentIndex() { return Math.round(track.scrollLeft / track.clientWidth); }
    function scrollToIndex(i) {
      i = Math.max(0, Math.min(slides.length - 1, i));
      track.scrollTo({ left: i * track.clientWidth, behavior: 'smooth' });
    }

    function update() {
      var idx = currentIndex();
      dots.forEach(function (d, i) { d.classList.toggle('is-active', i === idx); });
      if (prevBtn) prevBtn.disabled = idx <= 0;
      if (nextBtn) nextBtn.disabled = idx >= slides.length - 1;

      if (counterEl) {
        counterEl.innerHTML = '&nbsp;•&nbsp;' + (idx + 1) + '&nbsp;/&nbsp;' + slides.length;
      }
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { scrollToIndex(currentIndex() - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { scrollToIndex(currentIndex() + 1); });

    var ticking = false;
    track.addEventListener('scroll', function () {
      if (!ticking) { window.requestAnimationFrame(function () { update(); ticking = false; }); ticking = true; }
    });
    window.addEventListener('resize', update);
    update();
  }

  function boot() {
    document.querySelectorAll('[data-testimonials]').forEach(function (root) {
      initSlider(root);
    });
  }
  
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
