(function () {
  var EMAIL_PATTERN = /^[^\s@]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$/;

  function init() {
    setupDateMasks();
    setupPhoneMasks();
    setupEmailSanitizers();

    document.querySelectorAll('form[data-ac-form]').forEach(function (form) {
      setupForm(form);
    });

    setupLeadFormAutofocus();
  }

  function setupDateMasks() {
    document.querySelectorAll('[data-mask="date"]').forEach(function (field) {
      function applyMask() {
        var digits = field.value.replace(/\D/g, '').slice(0, 8);
        var output = digits.slice(0, 2);

        if (digits.length >= 3) output += '.' + digits.slice(2, 4);
        if (digits.length >= 5) output += '.' + digits.slice(4, 8);

        if (field.value !== output) field.value = output;
      }

      field.addEventListener('input', applyMask);
      field.addEventListener('blur', applyMask);
      field.addEventListener('change', applyMask);
    });
  }

  function setupPhoneMasks() {
    document.querySelectorAll('[data-mask="phone"]').forEach(function (field) {
      field.addEventListener('keydown', function (event) {
        var specialKeys = [8, 9, 13, 27, 35, 36, 37, 38, 39, 40, 46];
        if (specialKeys.indexOf(event.keyCode) !== -1) return;
        if ((event.ctrlKey || event.metaKey) && [65, 67, 86, 88].indexOf(event.keyCode) !== -1) return;
        if (event.key === '+' && field.selectionStart === 0 && field.value.indexOf('+') === -1) return;
        if (/^\d$/.test(event.key)) return;
        event.preventDefault();
      });

      field.addEventListener('input', function () {
        field.value = sanitizePhone(field.value);
      });

      field.addEventListener('paste', function (event) {
        event.preventDefault();
        var pasted = (event.clipboardData || window.clipboardData).getData('text');
        field.value = sanitizePhone(pasted);
      });
    });
  }

  function sanitizePhone(value) {
    var hasPlus = value.charAt(0) === '+';
    var digits = value.replace(/\D/g, '');
    return (hasPlus ? '+' : '') + digits;
  }

  function setupEmailSanitizers() {
    document.querySelectorAll('form[data-ac-form] input[type="email"]').forEach(function (field) {
      function sanitize() {
        field.value = field.value.replace(/[^\x20-\x7E]/g, '');
      }

      field.addEventListener('input', sanitize);
      field.addEventListener('paste', function () {
        setTimeout(sanitize, 0);
      });
    });
  }

  function setupForm(form) {
    var fields = Array.prototype.slice.call(form.querySelectorAll('[data-validate], [required]'));
    var submitButton = form.querySelector('[type="submit"]');
    var formError = form.querySelector('[data-form-error]');
    var formContainer = form.closest('.ac-card, .ac-action') || form.parentElement;
    var success = formContainer ? formContainer.querySelector('[data-form-success]') : null;

    fields.forEach(function (field) {
      field.addEventListener('blur', function () {
        setFieldState(field, validateField(field));
      });

      var eventName = field.tagName === 'SELECT' ? 'change' : 'input';
      field.addEventListener(eventName, function () {
        if (field.classList.contains('is-error')) {
          setFieldState(field, validateField(field));
        }
      });
    });

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      if (form.getAttribute('aria-busy') === 'true') return;

      var firstInvalidField = null;
      fields.forEach(function (field) {
        var message = validateField(field);
        setFieldState(field, message);
        if (message && !firstInvalidField) firstInvalidField = field;
      });

      if (firstInvalidField) {
        firstInvalidField.focus();
        return;
      }

      submitForm(form, submitButton, formError, success, fields);
    });
  }

  function validateField(field) {
    if (field.disabled) return '';

    var value = typeof field.value === 'string' ? field.value.trim() : '';
    var validator = field.getAttribute('data-validate') || field.type || 'required';

    if (field.required && !value) {
      if (validator === 'full-name') return 'Please enter the student\'s full name';
      if (validator === 'date') return 'Please enter the date of birth';
      if (validator === 'email') return 'Please enter your email';
      if (validator === 'phone') return 'Please enter a contact phone number';
      if (field.tagName === 'SELECT') return 'Please select an option';
      return 'Please complete this field';
    }

    if (!value) return '';

    if (validator === 'full-name') return validateFullName(value);
    if (validator === 'date') return validateDate(value);
    if (validator === 'email' && !EMAIL_PATTERN.test(value)) return 'Enter a valid email address';

    if (validator === 'phone') {
      var digits = value.replace(/\D/g, '');
      if (digits.length < 7) return 'Enter at least 7 digits';
    }

    if (field.validity && !field.validity.valid) return field.validationMessage || 'Please check this field';
    return '';
  }

  function validateFullName(value) {
    if (!/^[\p{L}\s\-']+$/u.test(value)) return 'Only letters, spaces and hyphens allowed';

    var parts = value.split(/\s+/).filter(Boolean);
    if (parts.length < 2) return 'Please enter both first and last name';
    if (parts.some(function (part) { return part.length < 2; })) return 'Each name part must be at least 2 characters';
    return '';
  }

  function validateDate(value) {
    var match = /^(\d{2})\.(\d{2})\.(\d{4})$/.exec(value);
    if (!match) return 'Use the format dd.mm.yyyy';

    var day = Number(match[1]);
    var month = Number(match[2]);
    var year = Number(match[3]);

    if (month < 1 || month > 12) return 'Month must be between 01 and 12';
    if (day < 1 || day > 31) return 'Day must be between 01 and 31';
    if (year < 1990 || year > 2020) return 'Year must be between 1990 and 2020';

    var date = new Date(year, month - 1, day);
    if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
      return 'Enter a valid calendar date';
    }

    return '';
  }

  function setFieldState(field, message) {
    var fieldContainer = field.closest('.ac-field');
    var error = fieldContainer ? fieldContainer.querySelector('.ac-field-error') : null;
    if (error) error.textContent = message;

    field.classList.toggle('is-error', Boolean(message));
    field.classList.toggle('is-valid', !message && Boolean(field.value.trim()));
    field.setAttribute('aria-invalid', message ? 'true' : 'false');
  }

  async function submitForm(form, submitButton, formError, success, fields) {
    var action = form.getAttribute('action');
    if (!action || !action.trim()) {
      showFormError(formError, 'The form endpoint is not configured.');
      return;
    }

    form.setAttribute('aria-busy', 'true');
    if (submitButton) submitButton.disabled = true;
    showFormError(formError, '');

    try {
      var response = await fetch(form.action, {
        method: (form.getAttribute('method') || 'post').toUpperCase(),
        body: new FormData(form),
        headers: {
          Accept: 'application/json'
        },
        credentials: 'same-origin'
      });

      if (response.status !== 200) {
        throw new Error('Unexpected response status: ' + response.status);
      }

      form.reset();
      fields.forEach(function (field) {
        setFieldState(field, '');
      });
      if (success) success.classList.add('is-visible');
    } catch (error) {
      showFormError(formError, 'We could not send the form. Please try again.');
    } finally {
      form.removeAttribute('aria-busy');
      if (submitButton) submitButton.disabled = false;
    }
  }

  function showFormError(element, message) {
    if (element) element.textContent = message;
  }

  function setupLeadFormAutofocus() {
    var nameField = document.querySelector('#lead [data-validate="full-name"]');
    if (!nameField || !('IntersectionObserver' in window)) return;
    if (!window.matchMedia('(min-width: 900px) and (pointer: fine)').matches) return;

    var leadSection = document.getElementById('lead');
    if (!leadSection) return;

    var observer = new IntersectionObserver(function (entries) {
      if (!entries[0].isIntersecting) return;

      observer.disconnect();
      if (nameField.value.trim() === '') {
        try {
          nameField.focus({ preventScroll: true });
        } catch (error) {
          nameField.focus();
        }
      }
    }, { threshold: 0.2 });

    setTimeout(function () {
      observer.observe(leadSection);
    }, 100);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
