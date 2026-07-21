/* ---- Date-of-birth mask: dd.mm.yyyy ---- */
document.addEventListener('DOMContentLoaded', function() {
  var dob = document.getElementById('f-dob');
  if (!dob) return;
  
  function applyMask() {
    var raw = dob.value;
    // Remove all non-digits
    var d = raw.replace(/\D/g, '').slice(0, 8);
    var out = '';
    
    if (d.length > 0) {
      out = d.slice(0, 2);
      if (d.length >= 3) {
        out += '.' + d.slice(2, 4);
      }
      if (d.length >= 5) {
        out += '.' + d.slice(4, 8);
      }
    }
    
    // Only update if changed to avoid jumping cursor issues if possible
    if (dob.value !== out) {
      dob.value = out;
    }
  }

  dob.addEventListener('input', applyMask);
  dob.addEventListener('blur', applyMask);
  dob.addEventListener('change', applyMask);
});

/* ---- Phone mask: + and digits only ---- */
document.addEventListener('DOMContentLoaded', function() {
  var ph = document.getElementById('f-phone');
  if (!ph) return;
  ph.addEventListener('keydown', function (e) {
    var special = [8, 9, 13, 27, 35, 36, 37, 38, 39, 40, 46];
    if (special.indexOf(e.keyCode) !== -1) return;
    if ((e.ctrlKey || e.metaKey) && [65, 67, 86, 88].indexOf(e.keyCode) !== -1) return;
    if (e.key === '+' && ph.selectionStart === 0 && ph.value.indexOf('+') === -1) return;
    if (/^\d$/.test(e.key)) return;
    e.preventDefault();
  });
  ph.addEventListener('input', function () {
    var raw = ph.value;
    var hasPlus = raw.charAt(0) === '+';
    var digits = raw.replace(/\D/g, '');
    ph.value = (hasPlus ? '+' : '') + digits;
  });
  ph.addEventListener('paste', function (e) {
    e.preventDefault();
    var pasted = (e.clipboardData || window.clipboardData).getData('text');
    var hasPlus = pasted.charAt(0) === '+';
    var digits = pasted.replace(/\D/g, '');
    ph.value = (hasPlus ? '+' : '') + digits;
  });
});

/* ---- Email mask: ASCII only (no Cyrillic) ---- */
document.addEventListener('DOMContentLoaded', function() {
  var em = document.getElementById('f-email');
  if (!em) return;
  em.addEventListener('input', function () {
    em.value = em.value.replace(/[^\x20-\x7E]/g, '');
  });
  em.addEventListener('paste', function () {
    setTimeout(function () {
      em.value = em.value.replace(/[^\x20-\x7E]/g, '');
    }, 0);
  });
});

/* ---- Form validation ---- */
(function () {
  var form    = document.getElementById('lead-form');
  var btn     = document.getElementById('submit-btn');
  var success = document.getElementById('form-success');
  if (!form || !btn) return;

  /* --- Validators --- */
  function valName(v) {
    v = v.trim();
    if (!v) return 'Please enter the student\'s full name';
    if (!/^[\p{L}\s\-']+$/u.test(v)) return 'Only letters, spaces and hyphens allowed';
    var parts = v.split(/\s+/).filter(Boolean);
    if (parts.length < 2) return 'Please enter both first and last name';
    if (parts.some(function(p){ return p.length < 2; })) return 'Each name part must be at least 2 characters';
    return '';
  }
  function valDob(v) {
    v = v.trim();
    if (!v) return 'Please enter the date of birth';
    var m = /^(\d{2})\.(\d{2})\.(\d{4})$/.exec(v);
    if (!m) return 'Use the format dd.mm.yyyy';
    var day = +m[1], mon = +m[2], yr = +m[3];
    if (mon < 1 || mon > 12) return 'Month must be between 01 and 12';
    if (day < 1 || day > 31) return 'Day must be between 01 and 31';
    if (yr < 1990 || yr > 2020) return 'Year must be between 1990 and 2020';
    if (new Date(yr, mon - 1, day).getDate() !== day) return 'Enter a valid calendar date';
    return '';
  }
  function valSelect(v) {
    return v ? '' : 'Please select an option';
  }
  function valEmail(v) {
    v = v.trim();
    if (!v) return 'Please enter your email';
    if (!/^[^\s@]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$/.test(v)) return 'Enter a valid email address';
    return '';
  }
  function valPhone(v) {
    v = v.trim();
    if (!v) return 'Please enter a contact phone number';
    var digits = v.replace(/\D/g, '');
    if (digits.length < 7) return 'Enter at least 7 digits';
    return '';
  }

  /* Map field id → { validator fn, error el id } */
  var fields = [
    { el: document.getElementById('f-name'),     fn: valName,   err: document.getElementById('err-name') },
    { el: document.getElementById('f-dob'),      fn: valDob,    err: document.getElementById('err-dob') },
    { el: document.getElementById('f-grade'),    fn: valSelect, err: document.getElementById('err-grade') },
    { el: document.getElementById('f-consider'), fn: valSelect, err: document.getElementById('err-consider') },
    { el: document.getElementById('f-email'),    fn: valEmail,  err: document.getElementById('err-email') },
    { el: document.getElementById('f-phone'),    fn: valPhone,  err: document.getElementById('err-phone') }
  ].filter(function(f){ return f.el && f.err; });

  /* Show / hide error for one field */
  function setFieldState(f, msg) {
    f.err.textContent = msg;
    if (msg) {
      f.el.classList.add('is-error');
      f.el.classList.remove('is-valid');
    } else {
      f.el.classList.remove('is-error');
      if (f.el.value.trim()) f.el.classList.add('is-valid');
    }
  }

  /* Attach blur + input/change listeners to each field */
  fields.forEach(function(f) {
    /* Show error on blur (after user leaves the field) */
    f.el.addEventListener('blur', function () {
      setFieldState(f, f.fn(f.el.value));
    });
    /* Re-check live as user types / selects */
    var evt = (f.el.tagName === 'SELECT') ? 'change' : 'input';
    f.el.addEventListener(evt, function () {
      /* Only clear error live (don't add new error while typing) */
      if (f.el.classList.contains('is-error')) {
        setFieldState(f, f.fn(f.el.value));
      }
    });
  });

  /* Submit — show success overlay */
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    /* Final check in case button was somehow enabled without all fields valid */
    var allOk = fields.every(function(f) {
      var msg = f.fn(f.el.value);
      setFieldState(f, msg);
      return msg === '';
    });
    if (!allOk) { return; }
    /* Show success */
    if (success) success.classList.add('is-visible');
  });
})();

/* ---- Autofocus name field when form scrolls into view (desktop only) ---- */
document.addEventListener('DOMContentLoaded', function() {
  var nameEl = document.getElementById('f-name');
  if (!nameEl || !('IntersectionObserver' in window)) return;
  if (!window.matchMedia('(min-width: 900px) and (pointer: fine)').matches) return;
  
  var leadSection = document.getElementById('lead');
  if (!leadSection) return;
  
  var io = new IntersectionObserver(function (entries) {
    if (entries[0].isIntersecting) {
      io.disconnect();
      if (nameEl.value.trim() === '') {
        try { nameEl.focus({ preventScroll: true }); } catch (e) { nameEl.focus(); }
      }
    }
  }, { threshold: 0.2 });
  
  setTimeout(function() {
    io.observe(leadSection);
  }, 100);
});
