(function($) {
  'use strict';

  // Close other submenus when opening a new one
  $('.sidebar .nav-item').on('click', function(e) {
    if ($(this).hasClass('nav-item')) {
      var $this = $(this);
      
      // Close all other collapse items
      $('.sidebar .nav-item .collapse').not($this.find('.collapse')).collapse('hide');
    }
  });

  // Prevent closing when clicking inside submenu
  $('.sidebar .nav-item .collapse').on('click', function(e) {
    e.stopPropagation();
  });

  // Add active class to parent menu item when submenu is active
  $('.sidebar .nav-item .collapse .nav-link').each(function() {
    if ($(this).hasClass('active')) {
      $(this).closest('.collapse').addClass('show');
      $(this).closest('.nav-item').addClass('active');
    }
  });

})(jQuery);