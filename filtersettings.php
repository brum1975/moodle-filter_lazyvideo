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
 * @copyright 2012 Matthew Cannings, Sandwell College
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * code based on the following filters... 
 * Screencast (Mark Schall)
 * Soundcloud (Troy Williams) 
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $items = array();
    $items[] = new admin_setting_configtext('filter_lazyvideo_width', get_string('width', 'filter_lazyvideo'), '', '560', PARAM_INT);
    //$items[] = new admin_setting_configtext('filter_lazyvideo_height', get_string('height', 'filter_lazyvideo'), '', '420', PARAM_INT);
    $buttoncolor = array('red','orange','purple');
    $items[] = new admin_setting_configselect('filter_lazyvideo_buttoncolor', get_string('buttoncolor', 'filter_lazyvideo'), '', 'orange', $buttoncolor);
    

    
    
    
    foreach ($items as $item) {
        $item->set_updatedcallback('filter_tex_updatedcallback');
        $settings->add($item);
    }
}
