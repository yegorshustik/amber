/* Amber Council — testimonial slider (shared component)
   Markup: <div class="ac-slider" data-slider> with a .ac-slider__track of
   .ac-slide children, dots container [data-dots], and [data-prev]/[data-next]. */
(function () {
  function initSlider(root) {
    var track = root.querySelector('.ac-slider__track');
    if (!track) return;
    var slides = Array.prototype.slice.call(track.children);
    if (!slides.length) return;

    var dotsWrap = root.querySelector('[data-dots]');
    var prevBtn = root.querySelector('[data-prev]');
    var nextBtn = root.querySelector('[data-next]');
    var nav = root.querySelector('.ac-slider__nav');

    // Single slide — no controls needed
    if (slides.length < 2) {
      if (nav) nav.style.display = 'none';
      return;
    }

    var dots = [];
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

    function currentIndex() {
      return Math.round(track.scrollLeft / track.clientWidth);
    }
    function scrollToIndex(i) {
      i = Math.max(0, Math.min(slides.length - 1, i));
      track.scrollTo({ left: i * track.clientWidth, behavior: 'smooth' });
    }
    function update() {
      var idx = currentIndex();
      dots.forEach(function (d, i) { d.classList.toggle('is-active', i === idx); });
      if (prevBtn) prevBtn.disabled = idx <= 0;
      if (nextBtn) nextBtn.disabled = idx >= slides.length - 1;
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { scrollToIndex(currentIndex() - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { scrollToIndex(currentIndex() + 1); });

    var ticking = false;
    track.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(function () { update(); ticking = false; });
        ticking = true;
      }
    });
    window.addEventListener('resize', function () { update(); });

    update();
  }

  function boot() {
    document.querySelectorAll('[data-slider]').forEach(initSlider);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
