(function () {
  document.addEventListener('DOMContentLoaded', function() {
    var tables = document.querySelectorAll('.ac-prose table');
    tables.forEach(function(table) {
      if (!table.parentNode.classList.contains('ac-table-wrapper')) {
        var wrapper = document.createElement('div');
        wrapper.className = 'ac-table-wrapper';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
      }
    });
  });
})();
