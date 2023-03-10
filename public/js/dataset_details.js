
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

// addign <dl> <dd> <dt> elements to whitelist so we can use them in our popovers
var bootstrapSanitizerWhiteList = bootstrap.Tooltip.Default.allowList

bootstrapSanitizerWhiteList.dl = []
bootstrapSanitizerWhiteList.dt = []
bootstrapSanitizerWhiteList.dd = []

/**
* Initialize Author popovers
*/
$(function () {
  $('.dataset-authors-section [data-bs-toggle="popover"]').popover({
     'html':true,
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
  $('.publishers-list [data-bs-toggle="popover"]').popover({
     'html':true,
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


  // initialize subject of study popovers
  $('#subject-of-study [data-bs-toggle="popover"]').popover({
     'html':true,
     'animation':false,
     'container':'body',
     'trigger':'manual',
     'placement':'right',
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


  // initialize local expert popovers
  $('.local-expert-popover-link').popover({
     'html':true,
     'animation':false,
     'trigger':'manual',
     'placement':'right',
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
  // track local expert contact form links if present
  $(document).on('click', '.local-expert-contact-link', function() {
      var url = null;
      var label = $(this).siblings("div").attr('id');
      trackOutboundLink(url, label);
  });
})


/**
* Initialize Project popovers
*/
$(function () {
  $('.dataset-detail-projects [data-bs-toggle="popover"]').popover({
     'html':true,
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
})
