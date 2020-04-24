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
 * Functions to support the generating/managing temporary access keys
 */
jQuery(function($) {

  /**
   * Show the modal form when the user clicks on an "Add New" link
   */
  $('.btn-tak-add').click(function(e) {
    var fieldName = $(this).attr('data-fieldname');
    e.preventDefault();
    var url = '/tak/gen/' + $('#dataset_dataset_uid').attr('value'); // $(this).attr('href');
    $.get(url, function(data) {
      $("#addEntityFormModalContent").html(data);
      $("#addEntityFormModal").modal({show:true});
    
    });
  });

	$('div.tak-panel').on('click', '.btn-tak-remove', removeTAK);
	

});
 
 
function removeTAK(e) {

	$.ajax({
		type: 'get',
		url:  '/tak/delete/'+$(this).parent().data('tak-uid'),
		success: function(data) {
			if (data['response']=='SUCCESS') {
				console.log($("div[data-tak-uid='"+data['uuid']+"']"));
				$("div[data-tak-uid='"+data['uuid']+"']").parent().remove();
			}
		}
	});


}