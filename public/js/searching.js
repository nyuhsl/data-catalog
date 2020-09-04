/**
 *
 *   This file is part of the Data Catalog project, except for the 
 *   exceptions noted in the comments.
 *   Copyright (C) 2016 NYU Health Sciences Library
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * Helpers and behaviors for dealing with searching and search results
 */
jQuery(function($) {

  /**
   * Interactivity with breadcrumbs
   */
  $('.keyword-breadcrumb.btn').hover(
    function() {
      $(this).attr('class','keyword-breadcrumb btn btn-sm btn-outline-danger');
      $(this).append('<i class="icon-remove-sign"></i>');
    },
    function() {
      $(this).attr('class','keyword-breadcrumb btn btn-sm btn-outline-secondary');
      $(this).children('.icon-remove-sign').remove();
  });


  /**
   * Call alterSearch() when a breadcrumb is removed
   */
  $('.keyword-breadcrumb').on('click', function(e) {
    e.preventDefault && e.preventDefault();
    $(this).fadeOut(150);
    alterSearch('remove', 'keyword', null);
    return false;
  });


  /**
   * If you want different behavior for Facet breadcrumbs, you can specify that here
   */
  $('.facetBreadcrumb.btn').on({
    mouseover: function() {
      $(this).attr('class','facetBreadcrumb btn btn-sm btn-outline-danger');
      $(this).append('<i class="icon-remove-sign"></i>');
    },
    mouseout: function() {
      $(this).attr('class','facetBreadcrumb btn btn-sm btn-outline-secondary');
      $(this).children('.icon-remove-sign').remove();
    }
  });


  /**
   * Handle institution-only filter
   */
  $('#internal-only-filter-container').on('click', function(e) {
      var content = "origin_fq:Internal";
      if ($('#internal-only-filter').attr('checked')) {
          alterSearch('remove', 'facet', content);
      } else {
          alterSearch('add', 'facet', content);
      }

  });

  /**
   * Call alterSearch() when a facet is removed
   */
  $('.facetBreadcrumb').on('click', function(e) {
    e.preventDefault && e.preventDefault();
    var content = $(this).attr('data-machine-name');

    $(this).fadeOut();
    alterSearch('remove', 'facet', content);
    return false;
  });


  /**
   * Handle keyword searches
   */
  var keywordForm = $('#keyword-search-form');
  var keywordInput = $('#keyword-search-input');
  keywordForm.on('submit', function(e) {
    e.preventDefault && e.preventDefault();
    var term = keywordInput.val();
    alterSearch('replace', 'keyword', term);
    return false;
  });
  keywordForm.on('keyUp', function(e) {
    e.preventDefault && e.preventDefault();
    if (e.which===13) {
      var term = keywordInput.val();
      alterSearch('replace', 'keyword', term);
      return false;
    }
  });


  /**
   * Handle page selection
   */
  var pagerInput = $('#pagenum-input');
  var maxPages = parseInt(pagerInput.attr('max'));
  var minPages = parseInt(pagerInput.attr('min'));
  pagerInput.on('keyup', function(e) {
    e.preventDefault && e.preventDefault();
    if (e.which===13) {
      var newPage = parseInt(pagerInput.val());
      if (newPage > maxPages) { newPage = maxPages; }
      if (newPage < minPages) { newPage = minPages; }
      alterSearch('jump', 'page', newPage);
      return false;
    }
  });


  /**
   * Handle sorting and pagination options
   */
  $('#sort-dropdown').on('change', function(e) {
    e.preventDefault && e.preventDefault();
    alterSearch('replace', 'sort', $('#sort-dropdown').val());
    return false;
  });
  $('#results_pp-dropdown').on('change', function(e) {
    e.preventDefault && e.preventDefault();
    alterSearch('replace', 'results', $('#results_pp-dropdown').val());
    return false;
  });


  /**
   * Solr-readable facet names are stored in the "data-solrfacetvalue" attributes.
   * When a user selects a facet, we call alterSearch() using a string that can ultimately
   * be read by Solr.
   */
  $('.facet-item a').on('click', function(e) {
    e.preventDefault && e.preventDefault();
    var category = $(this).attr('data-solrfacetcategory');
    var facetChoice = '"' + $(this).attr('data-solrfacetvalue') + '"';
    var facetString = category + ':' + facetChoice;
    //console.log(facetString);
    alterSearch('add', 'facet', facetString); 
    return false;
  });

  

  /**
   * Pager buttons
   */
  $('#nextPage-button, .pager-next').on('click', function(e) {
    e.preventDefault && e.preventDefault();
    alterSearch('next', 'page', null);
    return false;
  });
  $('#prevPage-button, .pager-previous').on('click', function(e) {
    e.preventDefault && e.preventDefault();
    alterSearch('previous', 'page', null);
    return false;
  });


  /**
   * This function does most of the leg work for searching. We chose to do all this with JS
   * because the search environment is dynamic and the parameters are complex, so we wanted to 
   * have the most control over it we could.
   *
   * Basically, you tell this function what you want to do, what parameter to do it to, and what content/data there is
   * The function creates an object representing the current search as read from the URL parameters.
   * Any adjustments are made and then a new URL is constructed and then navigated to.
   * I.e. alterSearch('remove''keyword',null) will remove the current keyword search
   * and alterSearch('add','facet','subject_domain_fq:"Delivery of Health Care"') will add that facet.
   */
  function alterSearch(action, target, content) {

    var params = $.deparam(window.location.search.substring(1));

    // handle page selection
    if (target == 'page') {
      var curPage = parseInt(params['page']) || 1;
      if (action=='next') {
        var goPage = curPage+1;
        params['page'] = goPage;
      } else if (action == 'jump') {
        params['page'] = content;
      } else if (curPage > 1) {
        var goPage = curPage-1;
        params['page'] = goPage;
      } else {
      }
    }
    // if we've changed the sorting, # of results per page, or ran a keyword search,
    // we need to go back to page 1
    if ((target == 'results') || (target == 'sort') || (target == 'keyword')) {
      params['page'] = 1;
    }

    // simply replace the old value with a new one
    if (action=='replace') {
      params[target] = content;
    }

    // Remove a parameter, but first check if we're dealing with an array that already contains
    // our content
    if (action=='remove') {
      if (params[target]) {
        if (content) {
          if (params[target].indexOf(content) > -1) {
            params[target].splice($.inArray(content, params[target]),1);
          }
        }
        else {
          params[target]=null;
        }
      }
    }
    

    // Add an item
    if (action=='add') {
      if (!params[target]) {
        target = target+'[]';
        params[target] = content;
        params['page'] = 1;
      }
      // if it's an array, check if it already contains this item
      else if ($.isArray(params[target])) {
        if (params[target].indexOf(content)==-1) {
          params[target].push(content);
          params['page'] = 1;
        } else {
          return false;
        }
      }
      else {
        $.makeArray(params[target]);
        params[target].push(content);
        params['page'] = 1;
      }

    }

    var newURL = "/search?" + $.param(params);
    window.location.href = newURL;
  }





  /**
   * http://stackoverflow.com/a/10997390/11236
   */
  function updateURLParameter(url, param, paramVal){
      var newAdditionalURL = "";
      var tempArray = url.split("?");
      var baseURL = tempArray[0];
      var additionalURL = tempArray[1];
      var temp = "";
      if (additionalURL) {
          tempArray = additionalURL.split("&");
          for (i=0; i<tempArray.length; i++){
              if(tempArray[i].split('=')[0] != param){
                  newAdditionalURL += temp + tempArray[i];
                  temp = "&";
              }
          }
      }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
  }


  /**
   * http://stackoverflow.com/a/21903119
   */
  function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
  }

});




/**
 * Originally from the jQuery BBQ library, extracted by AceMetrix at
 * https://github.com/AceMetrix/jquery-deparam
 *
 * License info for this function is appended to this application's LICENSE file
 * 
 */
(function ($) {
  $.deparam = function (params, coerce) {
    var obj = {},
        coerce_types = { 'true': !0, 'false': !1, 'null': null };

    // Iterate over all name=value pairs.
    $.each(params.replace(/\+/g, ' ').split('&'), function (j,v) {
      var param = v.split('='),
          key = decodeURIComponent(param[0]),
          val,
          cur = obj,
          i = 0,

          // If key is more complex than 'foo', like 'a[]' or 'a[b][c]', split it
          // into its component parts.
          keys = key.split(']['),
          keys_last = keys.length - 1;

      // If the first keys part contains [ and the last ends with ], then []
      // are correctly balanced.
      if (/\[/.test(keys[0]) && /\]$/.test(keys[keys_last])) {
        // Remove the trailing ] from the last keys part.
        keys[keys_last] = keys[keys_last].replace(/\]$/, '');

        // Split first keys part into two parts on the [ and add them back onto
        // the beginning of the keys array.
        keys = keys.shift().split('[').concat(keys);

        keys_last = keys.length - 1;
      } else {
        // Basic 'foo' style key.
        keys_last = 0;
      }

      // Are we dealing with a name=value pair, or just a name?
      if (param.length === 2) {
        val = decodeURIComponent(param[1]);

        // Coerce values.
        if (coerce) {
          val = val && !isNaN(val)              ? +val              // number
              : val === 'undefined'             ? undefined         // undefined
              : coerce_types[val] !== undefined ? coerce_types[val] // true, false, null
              : val;                                                // string
        }

        if ( keys_last ) {
          // Complex key, build deep object structure based on a few rules:
          // * The 'cur' pointer starts at the object top-level.
          // * [] = array push (n is set to array length), [n] = array if n is
          //   numeric, otherwise object.
          // * If at the last keys part, set the value.
          // * For each keys part, if the current level is undefined create an
          //   object or array based on the type of the next keys part.
          // * Move the 'cur' pointer to the next level.
          // * Rinse & repeat.
          for (; i <= keys_last; i++) {
            key = keys[i] === '' ? cur.length : keys[i];
            cur = cur[key] = i < keys_last
              ? cur[key] || (keys[i+1] && isNaN(keys[i+1]) ? {} : [])
              : val;
          }

        } else {
          // Simple key, even simpler rules, since only scalars and shallow
          // arrays are allowed.

          if ($.isArray(obj[key])) {
            // val is already an array, so push on the next value.
            obj[key].push( val );

          } else if (obj[key] !== undefined) {
            // val isn't an array, but since a second value has been specified,
            // convert val into an array.
            obj[key] = [obj[key], val];

          } else {
            // val is a scalar.
            obj[key] = val;
          }
        }

      } else if (key) {
        // No value was defined, so set something meaningful.
        obj[key] = coerce
          ? undefined
          : '';
      }
    });

    return obj;
  };
})(jQuery);
