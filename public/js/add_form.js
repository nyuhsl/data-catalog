/*
 *
 *   This file is part of the Data Catalog project.
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
 * Functions and behaviors for working with our data entry forms
 */
jQuery(function($) {

  // Set up Select2.js on all <select> elements
  var allSelects = $('select');
  allSelects.select2({
    width:'resolve',
  });

  // Produce the "Add New" links where necessary
  $.each(allSelects, makeAddNewLink);


  /**
  * If the <select> box accepts multiple choices, produce an "Add New" link by seeing what entity this field
  * is for, and constructing a URL for the modal form
  */
  function makeAddNewLink() {
    sel = $(this);
    if (sel.attr('multiple') !== undefined) {
      var thisField = sel.attr('id').replace('dataset_as_admin_','');
      thisField = toTitleCase(thisField.replace(/_/g,' ')).replace(/ /g,'');
      if (thisField.charAt(thisField.length - 1) == 's') {
        thisField = thisField.substr(0, thisField.length - 1);
      }
      var addNewLink = "<a href='/add/"+thisField+"?modal=true' class='addNewEntity'>Add new</a>";
      sel.parent('div').append(addNewLink);
    }
  }


  /**
   * Make sure we can remove items from embedded forms
   */
  var removeItemLink   = '<a class="btn-remove btn btn-danger" >Remove item</a>';
  $('.form-group.multiple div[data-content]').append(removeItemLink);


  /**
   * Show the modal form when the user clicks on an "Add New" link
   */
  $('.addNewEntity').click(function(e) {
    var fieldName = $(this).attr('data-fieldname');
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function(data) {
      $("#addEntityFormModalContent").html(data);
      $("#addEntityFormModal").modal({show:true,backdrop:'static'});
    });
  });


  /**
   * When a new item is added via a modal form, we handle that form submission here,
   * and then add the new item to the <select> options list so we can use it right away.
   *
   * Note, we need to calculate the new item's database ID since Symfony uses it in
   * the options list
   */
  $('#addEntityFormModal').on('submit','form', function(e) {
    e.preventDefault();
    $('#loading-indicator').show();
    var form = $(this);
    postForm(form ,function(response) {
      $('#addEntityFormModalContent').html(response);
      // find out the new entity's name and type, and which <select> element we're dealing with here
      var displayName = $('#addEntityFormModalContent #entity-display-name').attr('data-displayname');
      var addedEntityName = $('#addEntityFormModalContent #added-entity-name').text().trim();
      var selectBoxId = 'select#dataset_as_admin_' + displayName.replace(/ /g,'_') +'s';
        //console.log(selectBoxId);
      if (!$(selectBoxId).length){selectBoxId = selectBoxId.slice(0,-1);}
      // Symfony uses the actual database IDs as the values in <select> option lists. To trick Symfony
      // into accepting our brand-new item, we need to calculate its database ID and use it as the option value
      //var optionsList = $(selectBoxId + ' option').map(function(){return $(this).attr("value");}).get();
      //var maxValue = Math.max.apply(Math, optionsList);
      //var nextOption = maxValue + 10; // if your database IDs increment by 10
      // finally add the new item to the options list
      
      //
      // Added, 6/28/2017, Joel Marchewka
      //
      // Fetching ID from data-id attribute of element
      var nextOption =  $('#addEntityFormModalContent #entity-display-name').attr('data-id');
     // console.log("nextOption:"+nextOption);      
      $(selectBoxId).append('<option value="'+nextOption+'">'+addedEntityName+'</option>');

      var currentVals = $(selectBoxId).val();
      if (currentVals) {
        currentVals[currentVals.length] = nextOption;
        $(selectBoxId).val(currentVals).trigger("change");
      }
      else {
        currentVals = [nextOption];
        $(selectBoxId).val(currentVals).trigger("change");
        }
      });
    return false;
  });


  /**
   * Serialize and post the modal forms
   */
  function postForm($form, callback) {
    var values = {};
    $.each($form.serializeArray(), function(i,field) {
      values[field.name] = field.value;
    });
    values.targetentity = $form.attr('data-targetentity');
    $.ajax({
      type: $form.attr('method'),
      url:  $form.attr('action')+'?modal=true',
      data: values,
      success: function(data) {
        callback(data);
      }
    });
  }



  /**
   * Handle embedded forms (i.e. forms that get added inside the main form, rather than in a modal)
   */
  $('.btn-success[data-target]').on('click', function(event) {
    var target = $(this).attr('data-target');
    var collectionHolder = $('.' + target);
    // create a link to remove this item
    var removeItemLink   = '<a class="btn-remove btn btn-danger" data-related="'+target+'">Remove item</a>';

    if (!collectionHolder.attr('data-counter')) {
      collectionHolder.attr('data-counter', collectionHolder.children().length);
    }

    // The template for the embedded form is stored in the "data-prototype" attribute
    var prototype = collectionHolder.attr('data-prototype');
    var form = prototype.replace(/__name__/g, collectionHolder.attr('data-counter'));
    collectionHolder.attr('data-counter', Number(collectionHolder.attr('data-counter')) + 1);
    // Append the form template to the parent element
    $(form).hide().appendTo(collectionHolder).fadeIn(300);

    collectionHolder.children('div[data-content]').last().append(removeItemLink);

    collectionHolder.find('div:empty').remove();
    // active Select2.js on any new child <select>s
    var childSelects = collectionHolder.find('select');
    childSelects.select2({width:'100%'});

    event && event.preventDefault();
  });


  /** 
   * Remove a form element from the DOM when the "Remove Item" links are clicked
   * Removing it from the DOM will ensure it doesn't get accidentally submitted
   */
  $(document).on('click', '.btn-remove', function(event) {
      $(this).closest('div[data-content]').fadeOut(300, function() { $(this).remove(); });
      event && event.preventDefault();
  });


  /**
   * Convert string to Title Case
   */
  function toTitleCase(str)
  {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
  }


});
