/* Amber Council — FAQ behaviour
   - Category tabs filter the question list (content stays in the DOM → SEO-safe).
   - Questions render open on load and collapse on the first user scroll. */
(function () {

  function initTabs(root) {
    var tabs = Array.prototype.slice.call(root.querySelectorAll('.ac-faq__tab'));
    var items = Array.prototype.slice.call(root.querySelectorAll('.ac-faq__q'));
    if (!tabs.length) return;

    function show(cat) {
      items.forEach(function (it) {
        it.hidden = !(cat === 'all' || it.getAttribute('data-cat') === cat);
      });
      tabs.forEach(function (t) {
        t.classList.toggle('is-active', t.getAttribute('data-cat') === cat);
      });
    }

    tabs.forEach(function (t) {
      t.addEventListener('click', function () { show(t.getAttribute('data-cat')); });
    });

    // Open the category from the URL hash if it matches a tab, else the first tab.
    var fromHash = (location.hash || '').replace('#', '');
    var matches = tabs.some(function (t) { return t.getAttribute('data-cat') === fromHash; });
    show(matches ? fromHash : tabs[0].getAttribute('data-cat'));
  }

  function collapseOnScroll() {
    var open = Array.prototype.slice.call(document.querySelectorAll('.ac-faq__q[open]'));
    if (!open.length) return;
    window.addEventListener('scroll', function () {
      open.forEach(function (d) { d.open = false; });
    }, { passive: true, once: true });
  }

  function boot() {
    document.querySelectorAll('[data-faq]').forEach(initTabs);
    collapseOnScroll();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
