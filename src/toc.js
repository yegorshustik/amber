/* Amber Council — Table of Contents
   Auto-builds a ToC from h2/h3 inside .ac-prose, adds anchors,
   smooth-scrolls, and highlights the active section on scroll.
   Drop-in: <nav class="ac-toc" data-toc></nav> + <article class="ac-prose">…</article> */
(function () {
  function slugify(text) {
    return text.toLowerCase().trim()
      .replace(/[^\w\s-]/g, "")
      .replace(/\s+/g, "-")
      .replace(/-+/g, "-");
  }

  function build() {
    var tocEl = document.querySelector("[data-toc]");
    var content = document.querySelector(".ac-prose");
    if (!tocEl || !content) return;

    var headings = content.querySelectorAll("h2, h3");
    if (!headings.length) { tocEl.style.display = "none"; return; }

    var used = {};
    var ul = document.createElement("ul");

    headings.forEach(function (h) {
      if (!h.id) {
        var base = slugify(h.textContent) || "section";
        var id = base, i = 2;
        while (used[id] || document.getElementById(id)) { id = base + "-" + i++; }
        h.id = id;
      }
      used[h.id] = true;

      var li = document.createElement("li");
      li.className = h.tagName === "H3" ? "toc--h3" : "toc--h2";
      var a = document.createElement("a");
      a.href = "#" + h.id;
      a.textContent = h.textContent;
      a.dataset.target = h.id;
      li.appendChild(a);
      ul.appendChild(li);
    });

    var title = document.createElement("p");
    title.className = "ac-toc__title";
    title.textContent = "On this page";
    tocEl.appendChild(title);
    tocEl.appendChild(ul);

    var links = tocEl.querySelectorAll("a");

    links.forEach(function (a) {
      a.addEventListener("click", function (e) {
        e.preventDefault();
        var target = document.getElementById(a.dataset.target);
        if (target) {
          target.scrollIntoView({ behavior: "smooth", block: "start" });
          history.replaceState(null, "", "#" + a.dataset.target);
        }
      });
    });

    if ("IntersectionObserver" in window) {
      var byId = {};
      links.forEach(function (a) { byId[a.dataset.target] = a; });
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            links.forEach(function (a) { a.classList.remove("is-active"); });
            var active = byId[entry.target.id];
            if (active) active.classList.add("is-active");
          }
        });
      }, { rootMargin: "-95px 0px -60% 0px", threshold: 0 });
      headings.forEach(function (h) { observer.observe(h); });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", build);
  } else {
    build();
  }
})();
