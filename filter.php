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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/filelib.php');

class filter_lazyvideo extends moodle_text_filter {

    public function setup($page, $context) {
        // This only requires execution once per request.
        static $jsinitialised = false;
        if (empty($jsinitialised)) {
            $page->requires->yui_module(
                    'moodle-filter_lazyvideo-lazyload',
                    'M.filter_lazyvideo.init_filter_lazyload',
                    array(array('courseid' => 0)));
            $jsinitialised = true;
        }
    }

    function filter($text, array $options = array()) {
        global $CFG;
		
        if (!is_string($text) or empty($text)) {
            // non string data can not be filtered anyway
            return $text;
        }
        if (stripos($text, '</a>') === false) {
            // performance shortcut - all regexes below end with the </a> tag,
            // if not present nothing can match
            return $text;
        }

        $newtext = $text; // we need to return the original value if regex fails!

		//http://webplayer.clickview.co.uk/?p=O8Hv
		//$search = '/<a\s[^>]*href="https?:\/\/webplayer\.clickview\.(co\.uk|com\.au)\/\?p=(.*?)">(.*?)<\/a>/is';
		//$search = '/<a\s[^>]*href="((https?:\/\/(www\.)?screencast\.com)\/t\/(.*?))"(.*?)>(.*?)<\/a>/is';
		//$search = '/<a\s[^>]*href="http:\/\/soundcloud\.com\/([0-9A-Za-z]+)\/([0-9A-Za-z-]+)(?:\/([0-9A-Za-z-]+))?[^>]*>([^>]*)<\/a>/is';
		//$search = '/<a\s[^>]*href="((https?:\/\/(www\.)?youtube\.com)\/(.*?)(v=)(.{11}?)(.*?))"(.*?)>(.*?)<\/a>/is';
		$search = '/<a\s[^>]*href="(https?:\/\/(www\.)?)(youtube\.com|youtu\.be|youtube\.googleapis.com)\/(?:embed\/|v\/|watch\?v=|watch\?.+&amp;v=|watch\?.+&v=)?((\w|-){11})(.*?)"(.*?)>(.*?)<\/a>/is';
        $newtext = preg_replace_callback($search, 'filter_lazyvideo_callback', $newtext);
		if (empty($newtext) or $newtext === $text) {
            // error or not filtered
            unset($newtext);
            return $text;
        }

        return $newtext;
    }
}

function filter_lazyvideo_callback($link) {
    global $CFG;
    $url = "https://gdata.youtube.com/feeds/api/videos/".trim($link[4])."?v=2";
    $crl = curl_init();
	$timeout = 5;
    $dimensions = '';
	curl_setopt ($crl, CURLOPT_URL,$url);
	curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$ret = curl_exec($crl);
	curl_close($crl);
	$sPattern = "/<title>(.*?)<\/title>/s";
	preg_match($sPattern,$ret,$aMatch);
	$rPattern = "/<yt:duration seconds='([0-9]*?)'\/>/s";
	preg_match($rPattern,$ret,$bMatch);
	$seconds = $bMatch[1];
	if($seconds < 3600){
	 $format = 'i:s';
	}else{
	 $format = 'G:i:s';
	}
    if ($CFG->filter_lazyvideo_width > 0){
        $dimensions .= 'style="max-width:'.$CFG->filter_lazyvideo_width.'px" ';
    }

	$embedcode = '<a class="lazyvideo" href="#" data-code="'.trim($link[4]).'"><div class="lazyvideo_container" '.$dimensions.'>';
	$embedcode .= '<img class="lazyvideo_placeholder" src="http://img.youtube.com/vi/'.trim($link[4]).'/hqdefault.jpg" />';
	$embedcode .= '<div class="lazyvideo_title"><div class="lazyvideo_text">'.$aMatch[1].'</div></div>';
	$embedcode .= '<div class="lazyvideo_footer"><div class="lazyvideo_text">Duration - '.date($format, $seconds).'</div></div>';
	$embedcode .= '<span class="lazyvideo_playbutton'.$CFG->filter_lazyvideo_buttoncolor.'"></span>';
	$embedcode .= '</div></a>';    

	return $embedcode;
}
