jQuery(function($) {
 $(document).ready(function () {
  $('#collapsed-areas').on('shown.bs.collapse', function() {
      $('a.collapsed-toggle').text('See less...');
  });
  $('#collapsed-areas').on('hidden.bs.collapse', function() {
      $('a.collapsed-toggle').text('See more...');
  });
 });

});
