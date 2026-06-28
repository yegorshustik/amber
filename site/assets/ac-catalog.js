(function () {
  // Institution Database
  var INSTITUTIONS = [
    {
      id: 'eton',
      name: 'Eton College',
      type: 'school',
      typeName: 'Boarding School',
      country: 'UK',
      countryName: 'United Kingdom',
      city: 'Windsor',
      image: 'assets/ac-photo-eton.jpg',
      description: 'One of the world\'s most famous boarding schools, offering academic excellence, traditional values, and leadership preparation for boys.',
      url: 'school-detail-eton.html',
      specs: {
        age: '13–18',
        gender: 'Boys Only',
        boarding: 'Full Boarding',
        curriculum: 'GCSE, A-Level'
      }
    },
    {
      id: 'andover',
      name: 'Phillips Academy Andover',
      type: 'school',
      typeName: 'Boarding School',
      country: 'US',
      countryName: 'United States',
      city: 'Andover, MA',
      image: 'assets/ac-photo-hero.jpg', // fallback or reuse
      description: 'A prestigious, highly selective co-educational boarding school in New England, celebrated for its academic depth and global student body.',
      url: '#',
      specs: {
        age: '14–18',
        gender: 'Co-educational',
        boarding: 'Full / Day',
        curriculum: 'US Diploma, AP'
      }
    },
    {
      id: 'rosey',
      name: 'Institut Le Rosey',
      type: 'school',
      typeName: 'Boarding School',
      country: 'Switzerland',
      countryName: 'Switzerland',
      city: 'Rolle / Gstaad',
      image: 'assets/ac-photo-hero.jpg',
      description: 'Widely known as the "School of Kings" — a highly international Swiss boarding school featuring a dual-campus system (lake and mountain).',
      url: '#',
      specs: {
        age: '7–18',
        gender: 'Co-educational',
        boarding: 'Full Boarding',
        curriculum: 'IB, French Bac'
      }
    },
    {
      id: 'oxford',
      name: 'University of Oxford',
      type: 'university',
      typeName: 'University',
      country: 'UK',
      countryName: 'United Kingdom',
      city: 'Oxford',
      image: 'assets/ac-photo-oxford.jpg',
      description: 'The oldest university in the English-speaking world. Renowned for its unique collegiate system, one-on-one tutorials, and elite research.',
      url: 'university-detail-oxford.html',
      specs: {
        degrees: 'UG, PG, PhD',
        acceptance: '14%',
        campus: 'Collegiate',
        programs: 'PPE, STEM, Classics'
      }
    },
    {
      id: 'harvard',
      name: 'Harvard University',
      type: 'university',
      typeName: 'University',
      country: 'US',
      countryName: 'United States',
      city: 'Cambridge, MA',
      image: 'assets/ac-photo-hero.jpg',
      description: 'An elite Ivy League research university. Offers unmatched academic resources, global networking, and historically low acceptance rates.',
      url: '#',
      specs: {
        degrees: 'UG, PG, PhD',
        acceptance: '4%',
        campus: 'Traditional',
        programs: 'Liberal Arts, STEM, Law'
      }
    },
    {
      id: 'eth',
      name: 'ETH Zurich',
      type: 'university',
      typeName: 'University',
      country: 'Switzerland',
      countryName: 'Switzerland',
      city: 'Zurich',
      image: 'assets/ac-photo-hero.jpg',
      description: 'A world-leading public university for science and technology. Renowned for high academic standards and cutting-edge research programs.',
      url: '#',
      specs: {
        degrees: 'UG, PG, PhD',
        acceptance: '25%',
        campus: 'City / Modern',
        programs: 'STEM, Engineering'
      }
    }
  ];

  var activeFilters = {
    search: '',
    type: 'all',
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
    render();
  }

  function setupEventListeners() {
    // Search input
    var searchInput = document.getElementById('filter-search');
    if (searchInput) {
      searchInput.addEventListener('input', function (e) {
        activeFilters.search = e.target.value.toLowerCase().trim();
        render();
      });
    }

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
        render();
      });
    });

    // Country select
    var countrySelect = document.getElementById('filter-country');
    if (countrySelect) {
      countrySelect.addEventListener('change', function (e) {
        activeFilters.country = e.target.value;
        render();
      });
    }

    // School: Gender select
    var genderSelect = document.getElementById('filter-gender');
    if (genderSelect) {
      genderSelect.addEventListener('change', function (e) {
        activeFilters.gender = e.target.value;
        render();
      });
    }

    // School: Boarding select
    var boardingSelect = document.getElementById('filter-boarding');
    if (boardingSelect) {
      boardingSelect.addEventListener('change', function (e) {
        activeFilters.boarding = e.target.value;
        render();
      });
    }

    // University: Campus select
    var campusSelect = document.getElementById('filter-campus');
    if (campusSelect) {
      campusSelect.addEventListener('change', function (e) {
        activeFilters.campus = e.target.value;
        render();
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

  function render() {
    var gridEl = document.getElementById('catalog-grid');
    if (!gridEl) return;

    var filtered = INSTITUTIONS.filter(function (item) {
      // 1. Search text
      if (activeFilters.search) {
        var nameMatch = item.name.toLowerCase().indexOf(activeFilters.search) > -1;
        var descMatch = item.description.toLowerCase().indexOf(activeFilters.search) > -1;
        var cityMatch = item.city.toLowerCase().indexOf(activeFilters.search) > -1;
        if (!nameMatch && !descMatch && !cityMatch) return false;
      }

      // 2. Type tab
      if (activeFilters.type !== 'all' && item.type !== activeFilters.type) {
        return false;
      }

      // 3. Country dropdown
      if (activeFilters.country !== 'all' && item.country !== activeFilters.country) {
        return false;
      }

      // 4. School specific filters
      if (item.type === 'school') {
        if (activeFilters.gender !== 'all') {
          if (activeFilters.gender === 'coed' && item.specs.gender !== 'Co-educational') return false;
          if (activeFilters.gender === 'single' && item.specs.gender === 'Co-educational') return false;
        }
        if (activeFilters.boarding !== 'all') {
          if (activeFilters.boarding === 'full' && item.specs.boarding !== 'Full Boarding') return false;
          if (activeFilters.boarding === 'day-weekly' && item.specs.boarding === 'Full Boarding') return false;
        }
      }

      // 5. University specific filters
      if (item.type === 'university') {
        if (activeFilters.campus !== 'all') {
          if (activeFilters.campus === 'collegiate' && item.specs.campus !== 'Collegiate') return false;
          if (activeFilters.campus === 'city-modern' && item.specs.campus === 'Collegiate') return false;
        }
      }

      return true;
    });

    if (filtered.length === 0) {
      gridEl.innerHTML = 
        '<div class="ac-catalog-empty">' +
          '<h3 class="ac-h3">No institutions match your search.</h3>' +
          '<p>Try adjusting your filters or search keywords.</p>' +
        '</div>';
      return;
    }

    var html = '';
    filtered.forEach(function (item) {
      var specsHtml = '';
      if (item.type === 'school') {
        specsHtml = 
          '<div class="ac-catalog-card__spec-item"><strong>Age:</strong> ' + item.specs.age + '</div>' +
          '<div class="ac-catalog-card__spec-item"><strong>Gender:</strong> ' + item.specs.gender + '</div>' +
          '<div class="ac-catalog-card__spec-item"><strong>Boarding:</strong> ' + item.specs.boarding + '</div>' +
          '<div class="ac-catalog-card__spec-item"><strong>Curriculum:</strong> ' + item.specs.curriculum + '</div>';
      } else {
        specsHtml = 
          '<div class="ac-catalog-card__spec-item"><strong>Degrees:</strong> ' + item.specs.degrees + '</div>' +
          '<div class="ac-catalog-card__spec-item"><strong>Acceptance:</strong> ' + item.specs.acceptance + '</div>' +
          '<div class="ac-catalog-card__spec-item"><strong>Campus:</strong> ' + item.specs.campus + '</div>' +
          '<div class="ac-catalog-card__spec-item"><strong>Programs:</strong> ' + item.specs.programs + '</div>';
      }

      var cardLinkAttr = item.url === '#' ? 'class="ac-catalog-card is-placeholder"' : 'href="' + item.url + '" class="ac-catalog-card"';

      html +=
        '<' + (item.url === '#' ? 'div' : 'a') + ' ' + cardLinkAttr + '>' +
          '<div class="ac-catalog-card__img-wrap">' +
            '<img src="' + item.image + '" alt="' + item.name + '" class="ac-catalog-card__img">' +
            '<span class="ac-catalog-card__badge">' + item.typeName + '</span>' +
          '</div>' +
          '<div class="ac-catalog-card__body">' +
            '<div class="ac-catalog-card__loc">' + item.city + ', ' + item.countryName + '</div>' +
            '<h3 class="ac-catalog-card__title">' + item.name + '</h3>' +
            '<p class="ac-catalog-card__desc">' + item.description + '</p>' +
            '<div class="ac-catalog-card__specs">' + specsHtml + '</div>' +
            (item.url !== '#' ? '<span class="ac-catalog-card__link">View Profile <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></span>' : '<span class="ac-catalog-card__link is-placeholder">Profile Coming Soon</span>') +
          '</div>' +
        '</' + (item.url === '#' ? 'div' : 'a') + '>';
    });

    gridEl.innerHTML = html;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
