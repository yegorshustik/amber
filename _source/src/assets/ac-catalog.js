(function () {
  var activeFilters = {
    type: 'school',
    country: 'all',
    gender: 'all',
    boarding: 'all',
    campus: 'all'
  };

  function init() {
    var gridEl = document.getElementById('catalog-grid');
    if (!gridEl) return;

    setupEventListeners();
    updateSubFiltersVisibility();
    applyFilters();
  }
  function setupEventListeners() {
    // Type Tabs
    var typeButtons = document.querySelectorAll('[data-filter-type]');
    typeButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        typeButtons.forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        activeFilters.type = btn.getAttribute('data-filter-type');
        
        // Reset sub-filters when switching type
        activeFilters.gender = 'all';
        activeFilters.boarding = 'all';
        activeFilters.campus = 'all';
        
        resetSelects();
        updateSubFiltersVisibility();
        applyFilters();
      });
    });

    // Country select
    var countrySelect = document.getElementById('filter-country');
    if (countrySelect) {
      countrySelect.addEventListener('change', function (e) {
        activeFilters.country = e.target.value;
        applyFilters();
      });
    }

    // School: Gender select
    var genderSelect = document.getElementById('filter-gender');
    if (genderSelect) {
      genderSelect.addEventListener('change', function (e) {
        activeFilters.gender = e.target.value;
        applyFilters();
      });
    }

    // School: Boarding select
    var boardingSelect = document.getElementById('filter-boarding');
    if (boardingSelect) {
      boardingSelect.addEventListener('change', function (e) {
        activeFilters.boarding = e.target.value;
        applyFilters();
      });
    }

    // University: Campus select
    var campusSelect = document.getElementById('filter-campus');
    if (campusSelect) {
      campusSelect.addEventListener('change', function (e) {
        activeFilters.campus = e.target.value;
        applyFilters();
      });
    }
  }

  function resetSelects() {
    var genderSelect = document.getElementById('filter-gender');
    var boardingSelect = document.getElementById('filter-boarding');
    var campusSelect = document.getElementById('filter-campus');

    if (genderSelect) genderSelect.value = 'all';
    if (boardingSelect) boardingSelect.value = 'all';
    if (campusSelect) campusSelect.value = 'all';
  }

  function updateSubFiltersVisibility() {
    var schoolFilters = document.getElementById('sub-filters-school');
    var univFilters = document.getElementById('sub-filters-university');

    if (!schoolFilters || !univFilters) return;

    if (activeFilters.type === 'school') {
      schoolFilters.style.display = 'block';
      univFilters.style.display = 'none';
    } else if (activeFilters.type === 'university') {
      schoolFilters.style.display = 'none';
      univFilters.style.display = 'block';
    } else {
      schoolFilters.style.display = 'none';
      univFilters.style.display = 'none';
    }
  }

  function applyFilters() {
    var gridEl = document.getElementById('catalog-grid');
    if (!gridEl) return;

    var cards = gridEl.querySelectorAll('.ac-catalog-card');
    var visibleCount = 0;

    cards.forEach(function (card) {
      var isVisible = matchesFilters(card);
      card.hidden = !isVisible;
      if (isVisible) visibleCount += 1;
    });

    var emptyState = document.getElementById('catalog-empty');
    if (emptyState) emptyState.hidden = visibleCount !== 0;
  }

  function matchesFilters(card) {
    if (activeFilters.type !== 'all' && card.dataset.type !== activeFilters.type) {
      return false;
    }

    if (activeFilters.country !== 'all' && card.dataset.country !== activeFilters.country) {
      return false;
    }

    if (card.dataset.type === 'school') {
      if (activeFilters.gender !== 'all' && card.dataset.gender !== activeFilters.gender) {
        return false;
      }
      if (activeFilters.boarding !== 'all' && card.dataset.boarding !== activeFilters.boarding) {
        return false;
      }
    }

    if (card.dataset.type === 'university' && activeFilters.campus !== 'all' && card.dataset.campus !== activeFilters.campus) {
      return false;
    }

    return true;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
