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
 *
 */

jQuery(function($) {
 $(document).ready(function () {
  $('#collapsed-areas, #collapsed-authors').on('shown.bs.collapse', function() {
      $(this).siblings('a.collapsed-toggle').text('See less...');
  });
  $('#collapsed-areas, #collapsed-authors').on('hidden.bs.collapse', function() {
      $(this).siblings('a.collapsed-toggle').text('See more...');
  });
 });

});
