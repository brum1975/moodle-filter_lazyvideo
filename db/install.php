<?php
// This file is part of Moodle-lazyvideo-Filter
//
// Moodle-lazyvideo-Filter is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle-lazyvideo-Filter is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle-lazyvideo-Filter.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Filter for component 'filter_lazyvideo'
 *
 * @package   filter_lazyvideo
 * @copyright 2012 Matthew Cannings
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * code based on the following filters... 
 * Screencast (Mark Schall)
 * Soundcloud (Troy Williams) 
 */

function xmldb_filter_lazyvideo_install() {
    global $CFG;

    filter_set_global_state('filter/lazyvideo', TEXTFILTER_ON);
}

