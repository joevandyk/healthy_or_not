<?php
/*
Plugin Name: WP-Polls Widget
Plugin URI: http://lesterchan.net/portfolio/programming/php/
Description: Adds a Poll Widget to display single or multiple polls from WP-Polls Plugin. You will need to activate WP-Polls first.
Version: 2.31
Author: Lester 'GaMerZ' Chan
Author URI: http://lesterchan.net
*/


/*  
	Copyright 2008  Lester Chan  (email : lesterchan@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


### Function: Init WP-Polls Widget
function widget_polls_init() {
	if (!function_exists('register_sidebar_widget')) {
		return;
	}

	### Function: WP-Polls Widget
	function widget_polls($args) {
		global $in_pollsarchive;
		extract($args);
		$options = get_option('widget_polls');
		$title = htmlspecialchars(stripslashes($options['title']));		
		if (function_exists('vote_poll') && !in_pollarchive()) {
			echo $before_widget.$before_title.$title.$after_title;
			if(intval(get_option('poll_currentpoll')) == -3) {
				$multiple_polls = explode(',', $options['multiple_polls']);
				foreach($multiple_polls as $multiple_poll) {
					get_poll($multiple_poll);
				}
			} else {
				get_poll();				
			}
			display_polls_archive_link();
			echo $after_widget;
		}		
	}

	### Function: WP-Polls Widget Options
	function widget_polls_options() {
		global $wpdb;
		$options = get_option('widget_polls');
		$current_poll = get_option('poll_currentpoll');
		if (!is_array($options)) {
			$options = array('title' => __('Polls', 'wp-polls'), 'poll_multiplepolls' => '');
		}
		if ($_POST['polls-submit']) {
			$poll_currentpoll = intval($_POST['poll_currentpoll']);
			$poll_archive_show = intval($_POST['poll_archive_show']);		
			$options['title'] = strip_tags($_POST['polls-title']);
			if(is_array($_POST['poll_multiplepolls'])) {
				$options['multiple_polls'] = implode(',', $_POST['poll_multiplepolls']);
			} else {
				$options['multiple_polls'] = $_POST['poll_multiplepolls'];
			}
			update_option('widget_polls', $options);
			update_option('poll_currentpoll', $poll_currentpoll);
			update_option('poll_archive_show', $poll_archive_show);
		}
		?>
		<script type="text/javascript">
			/* <![CDATA[*/
				function show_multiple_polls() {
					if(document.getElementById('poll_currentpoll').value == -3) {
						document.getElementById('poll_multiplepolls').disabled = false;
						document.getElementById('poll_multiplepolls_text').style.display = 'block';
					} else {						
						document.getElementById('poll_multiplepolls').selectedIndex = -1;
						document.getElementById('poll_multiplepolls').disabled = true;
						document.getElementById('poll_multiplepolls_text').style.display = 'none';
					}
				}
			/* ]]> */
		</script>
		<?php
		echo '<p style="text-align: left;"><label for="polls-title">';
		_e('Title', 'wp-polls');
		echo ': </label><input type="text" id="polls-title" name="polls-title" value="'.htmlspecialchars(stripslashes($options['title'])).'" /></p>'."\n";
		echo '<p style="text-align: left;"><label for="polls-displayarchive">';
		_e('Display Polls Archive Link Below Poll?', 'wp-polls');
		echo ' </label>'."\n";
		echo '<select id="polls-displayarchive" name="poll_archive_show" size="1">'."\n";
		echo '<option value="0"';
		selected('0', get_option('poll_archive_show'));
		echo '>';
		_e('No', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="1"';
		selected('1', get_option('poll_archive_show'));
		echo '>';
		_e('Yes', 'wp-polls');
		echo '</option>'."\n";
		echo '</select></p>'."\n";
		echo '<p style="text-align: left;"><label for="poll_currentpoll">';
		_e('Current Active Poll', 'wp-polls');
		echo ': </label>'."\n";
		echo '<select id="poll_currentpoll" name="poll_currentpoll" size="1" onchange="show_multiple_polls()">'."\n";
		echo '<option value="-1"';
		selected(-1, $current_poll);
		echo '>';
		_e('Do NOT Display Poll (Disable)', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="-2"';
		selected(-2, $current_poll);
		echo '>';
		_e('Display Random Poll', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="0"';
		selected(0, $current_poll);
		echo '>';
		_e('Display Latest Poll', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="-3"';
		selected(-3, $current_poll);
		echo '>';
		_e('Display Multiple Polls', 'wp-polls');
		echo '</option>'."\n";
		echo '<option value="0">&nbsp;</option>'."\n";
		$polls = $wpdb->get_results("SELECT pollq_id, pollq_question FROM $wpdb->pollsq ORDER BY pollq_id DESC");
		if($polls) {
			foreach($polls as $poll) {
				$poll_question = stripslashes($poll->pollq_question);
				$poll_id = intval($poll->pollq_id);
				if($poll_id == intval($current_poll)) {
					echo "<option value=\"$poll_id\" selected=\"selected\">$poll_question</option>\n";
				} else {
					echo "<option value=\"$poll_id\">$poll_question</option>\n";
				}
			}
		}
		echo '</select></p>'."\n";
		if($current_poll == -3) {
			$display = 'display: block;';
			$disabled = '';
		} else {
			$display = 'display: none;';
			$disabled = 'disabled="disabled"';
		}
		echo '<p id="poll_multiplepolls_text" style="text-align: left; '.$display.'"><label for="poll_multiplepolls">';
		_e('Select Multiple Polls', 'wp-polls');
		echo ': </label>'."\n";
		echo '<select id="poll_multiplepolls" name="poll_multiplepolls[]" size="5" multiple="true" style="height: 100px; vertical-align: text-top;" $disabled>'."\n";
		$multiple_polls = explode(',', $options['multiple_polls']);
		$polls = $wpdb->get_results("SELECT pollq_id, pollq_question FROM $wpdb->pollsq ORDER BY pollq_id DESC");
		if($polls) {
			foreach($polls as $poll) {
				$poll_question = stripslashes($poll->pollq_question);
				$poll_id = intval($poll->pollq_id);
				if(in_array($poll_id, $multiple_polls)) {
					echo "<option value=\"$poll_id\" selected=\"selected\">$poll_question</option>\n";
				} else {
					echo "<option value=\"$poll_id\">$poll_question</option>\n";
				}
			}
		}
		echo '</select>'."\n";
		echo '</p>'."\n";
		echo '<input type="hidden" id="polls-submit" name="polls-submit" value="1" />'."\n";
	}

	// Register Widgets
	register_sidebar_widget(array('Polls', 'wp-polls'), 'widget_polls');
	register_widget_control(array('Polls', 'wp-polls'), 'widget_polls_options', 400, 300);
}


### Function: Load The WP-Polls Widget
add_action('plugins_loaded', 'widget_polls_init');
?>