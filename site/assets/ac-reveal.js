/**
 * ac-reveal.js
 * Asynchronous scroll reveal for Amber Council
 */
(function() {
  document.addEventListener("DOMContentLoaded", function() {
    // 1. Define what elements should be animated
    const selectors = [
      '.ac-h1', '.ac-h2', '.ac-h3',
      '.ac-eyebrow',
      '.ac-editorial',
      '.ac-hero__cta',
      '.ac-feature',
      '.ac-card',
      '.ac-step',
      '.ac-founder',
      '.ac-action',
      '.ac-photo',
      '.ac-img-ph',
      '.ac-slide',
      '.ac-pricing-tier',
      '.ac-faq__q'
    ].join(', ');

    const elements = document.querySelectorAll(selectors);
    
    // Only proceed if browser supports IntersectionObserver
    if (!('IntersectionObserver' in window)) return;

    // 2. Add base reveal class to all elements initially
    elements.forEach(function(el) {
      el.classList.add('ac-reveal');
    });

    let delayCounter = 0;
    let delayTimeout = null;

    // 3. Setup Observer
    const observer = new IntersectionObserver(function(entries, obs) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          // If element enters viewport, stagger its appearance
          entry.target.style.transitionDelay = (delayCounter * 100) + 'ms';
          
          // Use requestAnimationFrame to ensure the delay is applied before class addition
          requestAnimationFrame(function() {
            entry.target.classList.add('is-revealed');
          });
          
          obs.unobserve(entry.target);
          
          delayCounter++;
          if (delayTimeout) clearTimeout(delayTimeout);
          delayTimeout = setTimeout(function() { 
            delayCounter = 0; 
          }, 100);
        }
      });
    }, { 
      threshold: 0.1, 
      rootMargin: "0px 0px -50px 0px" 
    });

    // 4. Start observing
    elements.forEach(function(el) {
      observer.observe(el);
    });
  });
})();
