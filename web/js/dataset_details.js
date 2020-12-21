
/**
* Record outbound link clicks for Analytics
*/
var trackOutboundLink = function(url, label) {
  gtag('event', 'click', {
    'event_category': 'outbound',
    'event_label': label,
    'transport_type': 'beacon'
  });
}

/**
* Initialize page popovers
*/
$(function () {
  // initialize Author popovers
  $('.dataset-authors-section [data-toggle="popover"]').popover({
     'html':'true',
     'animation':false,
     'trigger':'manual',
     'placement':'bottom',
  }).on("mouseenter", function () {
    var _this = this;
    $(this).popover("show");
    $(".popover").on("mouseleave", function () {
      $(_this).popover('hide');
    });
  }).on("mouseleave", function () {
    var _this = this;
    setTimeout(function () {
        if (!$(".popover:hover").length) {
            $(_this).popover("hide");
        }
    }, 200);
  });

  // initialize Publisher popovers
  $('.publishers-list [data-toggle="popover"]').popover({
     'html':'true',
     'animation':false,
     'trigger':'manual',
     'placement':'top',
  }).on("mouseenter", function () {
    var _this = this;
    $(this).popover("show");
    $(".popover").on("mouseleave", function () {
      $(_this).popover('hide');
    });
  }).on("mouseleave", function () {
    var _this = this;
    setTimeout(function () {
        if (!$(".popover:hover").length) {
            $(_this).popover("hide");
        }
    }, 200);
  });
})
