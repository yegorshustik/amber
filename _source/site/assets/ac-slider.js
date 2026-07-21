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

    var prevBtn = root.querySelector('[data-prev]');
    var nextBtn = root.querySelector('[data-next]');
    var counterEl = root.querySelector('[data-counter]');

    // Find the eyebrow heading associated with this slider (sibling before it)
    var eyebrowEl = null;
    var container = root.parentElement;
    if (container) {
      eyebrowEl = container.querySelector('[data-slider-eyebrow]');
    }
    // Create the counter span for the eyebrow (mobile only)
    var eyebrowCounter = null;
    if (eyebrowEl) {
      eyebrowCounter = document.createElement('span');
      eyebrowCounter.className = 'ac-eyebrow__counter';
      eyebrowEl.appendChild(eyebrowCounter);
    }

    function currentIndex() { return Math.round(track.scrollLeft / track.clientWidth); }
    function scrollToIndex(i) {
      i = Math.max(0, Math.min(slides.length - 1, i));
      track.scrollTo({ left: i * track.clientWidth, behavior: 'smooth' });
    }

    function update() {
      var idx = currentIndex();
      slides.forEach(function (s, i) { s.classList.toggle('is-active', i === idx); });
      if (prevBtn) prevBtn.disabled = idx <= 0;
      if (nextBtn) nextBtn.disabled = idx >= slides.length - 1;

      if (counterEl) {
        counterEl.textContent = (idx + 1) + ' / ' + slides.length;
      }
      if (eyebrowCounter) {
        eyebrowCounter.textContent = ' · ' + (idx + 1) + '/' + slides.length;
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

    // Mouse drag-to-scroll support
    var isDown = false;
    var startX;
    var scrollLeft;

    track.addEventListener('mousedown', function (e) {
      isDown = true;
      track.classList.add('is-dragging');
      track.style.scrollSnapType = 'none'; // disable snap during drag
      startX = e.pageX - track.offsetLeft;
      scrollLeft = track.scrollLeft;
    });
    track.addEventListener('mouseleave', function () {
      if (!isDown) return;
      isDown = false;
      track.classList.remove('is-dragging');
      track.style.scrollSnapType = '';
    });
    track.addEventListener('mouseup', function () {
      if (!isDown) return;
      isDown = false;
      track.classList.remove('is-dragging');
      track.style.scrollSnapType = '';
    });
    track.addEventListener('mousemove', function (e) {
      if (!isDown) return;
      e.preventDefault();
      var x = e.pageX - track.offsetLeft;
      var walk = (x - startX) * 1.5;
      track.scrollLeft = scrollLeft - walk;
    });
  }

  function boot() {
    document.querySelectorAll('[data-testimonials]').forEach(function (root) {
      initSlider(root);
    });
  }
  
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
