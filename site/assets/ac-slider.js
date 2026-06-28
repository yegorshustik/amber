/* Amber Council — testimonials (single-source component)
   Edit the TESTIMONIALS array below — it renders into every
   <div data-testimonials></div> on any page, so one edit updates all. */
(function () {

  /* ===================== EDIT TESTIMONIALS HERE ===================== */
  var TESTIMONIALS = [
    {
      quote: "They were honest about what was realistic, then made it happen. For the first time we felt someone was genuinely accountable for our son's future.",
      name: "[Name Surname]",
      role: "Parent · Tri-City",
      photo: "assets/ac-avatar-yegor-sm.jpg", // optional; "" → person-icon placeholder
      social: ""                              // optional; if set, the name becomes a link
    },
    {
      quote: "[Placeholder testimonial — a parent's words about the result and what it felt like to work with Amber Council.]",
      name: "[Name Surname]",
      role: "Parent · Sopot",
      photo: "",
      social: ""
    },
    {
      quote: "[Placeholder testimonial — another short, specific quote about honesty, accountability or outcome.]",
      name: "[Name Surname]",
      role: "Parent · Gdynia",
      photo: "",
      social: ""
    }
  ];
  /* ================================================================= */

  var USER_ICON = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
  var CHEV_L = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>';
  var CHEV_R = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>';

  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function slideHTML(t, num, total) {
    var avatar = t.photo
      ? '<span class="ac-slide__avatar"><img src="' + esc(t.photo) + '" alt=""></span>'
      : '<span class="ac-slide__avatar" aria-hidden="true">' + USER_ICON + '</span>';
    var name = t.social
      ? '<a class="ac-slide__name" href="' + esc(t.social) + '" target="_blank" rel="noopener">' + esc(t.name) + '</a>'
      : '<span class="ac-slide__name">' + esc(t.name) + '</span>';
    return '<figure class="ac-slide">'
      + '<blockquote>"' + esc(t.quote) + '"</blockquote>'
      + '<figcaption class="ac-slide__by">' + avatar
      + '<span class="ac-slide__id">' + name
      + '<span class="ac-slide__role">' + esc(t.role) + ' <span class="ac-slide__counter-inline">&nbsp;•&nbsp; ' + num + ' / ' + total + '</span></span></span>'
      + '</figcaption></figure>';
  }

  function render(root) {
    var total = TESTIMONIALS.length;
    var slides = TESTIMONIALS.map(function (t, i) {
      return slideHTML(t, i + 1, total);
    }).join('');
    root.classList.add('ac-slider');
    root.innerHTML =
      '<div class="ac-slider__track">' + slides + '</div>'
      + '<div class="ac-slider__nav">'
      +   '<div class="ac-slider__arrows">'
      +     '<button class="ac-slider__btn" type="button" data-prev aria-label="Previous testimonial">' + CHEV_L + '</button>'
      +     '<button class="ac-slider__btn" type="button" data-next aria-label="Next testimonial">' + CHEV_R + '</button>'
      +   '</div>'
      +   '<div class="ac-slider__dots" data-dots></div>'
      + '</div>';
  }

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
      render(root);
      initSlider(root);
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
