<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.5 Plugin: WP-Polls 2.31										|
|	Copyright (c) 2008 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://lesterchan.net															|
|																							|
|	File Information:																	|
|	- Add Poll																			|
|	- wp-content/plugins/wp-polls/polls-add.php								|
|																							|
+----------------------------------------------------------------+
*/


### Check Whether User Can Manage Polls
if(!current_user_can('manage_polls')) {
	die('Access Denied');
}


### Poll Manager
$base_name = plugin_basename('wp-polls/polls-manager.php');
$base_page = 'admin.php?page='.$base_name;


### Form Processing 
if(!empty($_POST['do'])) {
	// Decide What To Do
	switch($_POST['do']) {
		// Add Poll
		case __('Add Poll', 'wp-polls'):
			// Poll Question
			$pollq_question = addslashes(trim($_POST['pollq_question']));
			// Poll Start Date
			$timestamp_sql = '';
			$pollq_timestamp_day = intval($_POST['pollq_timestamp_day']);
			$pollq_timestamp_month = intval($_POST['pollq_timestamp_month']);
			$pollq_timestamp_year = intval($_POST['pollq_timestamp_year']);
			$pollq_timestamp_hour = intval($_POST['pollq_timestamp_hour']);
			$pollq_timestamp_minute = intval($_POST['pollq_timestamp_minute']);
			$pollq_timestamp_second = intval($_POST['pollq_timestamp_second']);
			$pollq_timestamp = gmmktime($pollq_timestamp_hour, $pollq_timestamp_minute, $pollq_timestamp_second, $pollq_timestamp_month, $pollq_timestamp_day, $pollq_timestamp_year);
			if($pollq_timestamp > current_time('timestamp')) {
				$pollq_active = -1;
			} else {
				$pollq_active = 1;
			}
			// Poll End Date
			$pollq_expiry_no = intval($_POST['pollq_expiry_no']);
			if($pollq_expiry_no == 1) {
				$pollq_expiry = '';
			} else {
				$pollq_expiry_day = intval($_POST['pollq_expiry_day']);
				$pollq_expiry_month = intval($_POST['pollq_expiry_month']);
				$pollq_expiry_year = intval($_POST['pollq_expiry_year']);
				$pollq_expiry_hour = intval($_POST['pollq_expiry_hour']);
				$pollq_expiry_minute = intval($_POST['pollq_expiry_minute']);
				$pollq_expiry_second = intval($_POST['pollq_expiry_second']);
				$pollq_expiry = gmmktime($pollq_expiry_hour, $pollq_expiry_minute, $pollq_expiry_second, $pollq_expiry_month, $pollq_expiry_day, $pollq_expiry_year);
				if($pollq_expiry <= current_time('timestamp')) {
					$pollq_active = 0;
				}
			}
			// Mutilple Poll
			$pollq_multiple_yes = intval($_POST['pollq_multiple_yes']);
			$pollq_multiple = 0;
			if($pollq_multiple_yes == 1) {
				$pollq_multiple = intval($_POST['pollq_multiple']);
			} else {
				$pollq_multiple = 0;
			}
			// Insert Poll
			$add_poll_question = $wpdb->query("INSERT INTO $wpdb->pollsq VALUES (0, '$pollq_question', '$pollq_timestamp', 0, $pollq_active, '$pollq_expiry', $pollq_multiple, 0)");
			if(!$add_poll_question) {
				$text .= '<p style="color: red;">'.sprintf(__('Error In Adding Poll \'%s\'.', 'wp-polls'), stripslashes($pollq_question)).'</p>';
			}
			// Add Poll Answers
			$polla_answers = $_POST['polla_answers'];
			$polla_qid = intval($wpdb->insert_id);
			foreach($polla_answers as $polla_answer) {
				$polla_answer = addslashes(trim($polla_answer));
				$add_poll_answers = $wpdb->query("INSERT INTO $wpdb->pollsa VALUES (0, $polla_qid, '$polla_answer', 0)");
				if(!$add_poll_answers) {
					$text .= '<p style="color: red;">'.sprintf(__('Error In Adding Poll\'s Answer \'%s\'.', 'wp-polls'), stripslashes($polla_answer)).'</p>';
				}
			}
			// Update Lastest Poll ID To Poll Options
			$latest_pollid = polls_latest_id();
			$update_latestpoll = update_option('poll_latestpoll', $latest_pollid);
			if(empty($text)) {
				$text = '<p style="color: green;">'.sprintf(__('Poll \'%s\' Added Successfully.', 'wp-polls'), stripslashes($pollq_question)).' <a href="'.$base_page.'">'.__('Manage Polls', 'wp-polls').'</a></p>';
			}
			cron_polls_place();
			break;
	}
}

### Add Poll Form
$poll_noquestion = 2;
$count = 0;
?>
<script type="text/javascript">
	/* <![CDATA[*/
	function check_pollexpiry() {
		poll_expiry = document.getElementById("pollq_expiry_no").checked;
		if(poll_expiry) {
			document.getElementById("pollq_expiry").style.display = 'none';
		} else {
			document.getElementById("pollq_expiry").style.display = 'block';
		}
	}
	var count_poll_answer = <?php echo $poll_noquestion; ?>;
	function create_poll_answer() {
		// Create Elements
		var poll_tr = document.createElement("tr");
		var poll_td1 = document.createElement("th");
		var poll_td2 = document.createElement("td");
		var poll_answer = document.createElement("input");
		var poll_answer_count = document.createTextNode("<?php _e('Answer', 'wp-polls'); ?> " + (count_poll_answer+1));
		var poll_answer_bold = document.createElement("strong");
		var poll_option = document.createElement("option");
		var poll_option_text = document.createTextNode((count_poll_answer+1));
		count_poll_answer++;
		// Elements - Input
		poll_answer.setAttribute('type', "text");
		poll_answer.setAttribute('name', "polla_answers[]");
		poll_answer.setAttribute('size', "50");
		// Elements - Options
		poll_option.setAttribute('value', count_poll_answer);
		poll_option.setAttribute('id', "pollq-multiple-" + (count_poll_answer+1));
		// Elements - TD/TR
		poll_tr.setAttribute('id', "poll-answer-" + count_poll_answer);
		poll_td1.setAttribute('width', "20%");
		poll_td1.setAttribute('scope', "row");
		poll_td2.setAttribute('width', "80%");
		// Appending
		poll_tr.appendChild(poll_td1);
		poll_tr.appendChild(poll_td2);
		poll_answer_bold.appendChild(poll_answer_count);
		poll_td1.appendChild(poll_answer_bold);
		poll_td2.appendChild(poll_answer);
		poll_option.appendChild(poll_option_text);
		document.getElementById("poll_answers").appendChild(poll_tr);
		document.getElementById("pollq_multiple").appendChild(poll_option);
	}
	function remove_poll_answer() {
		if(count_poll_answer == 2) {
			alert("<?php _e('You need at least a minimum of 2 poll answers.', 'wp-polls'); ?>");
		} else {
			document.getElementById("poll_answers").removeChild(document.getElementById("poll-answer-" + count_poll_answer));
			document.getElementById("pollq_multiple").removeChild(document.getElementById("pollq-multiple-" + (count_poll_answer+1)));
			document.getElementById("pollq_multiple").value = count_poll_answer;
			count_poll_answer--;
		}
	}
	function check_pollq_multiple() {
		if(parseInt(document.getElementById("pollq_multiple_yes").value) == 1) {
			document.getElementById("pollq_multiple").disabled = false;
		} else {
			document.getElementById("pollq_multiple").value = 1;
			document.getElementById("pollq_multiple").disabled = true;
		}
	}
	/* ]]> */
</script>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade">'.stripslashes($text).'</div>'; } ?>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
<div class="wrap">
	<h2><?php _e('Add Poll', 'wp-polls'); ?></h2>
	<!-- Poll Question -->
	<h3><?php _e('Poll Question', 'wp-polls'); ?></h3>
	<table class="form-table">
		<tr>
			<th width="20%" scope="row" valign="top"><?php _e('Question', 'wp-polls') ?></th>
			<td width="80%"><input type="text" size="70" name="pollq_question" value="" /></td>
		</tr>
	</table>
	<!-- Poll Answers -->
	<h3><?php _e('Poll Answers', 'wp-polls'); ?></h3>
	<table class="form-table">
		<tfoot>
			<tr>
				<td width="20%">&nbsp;</td>
				<td width="80%"><input type="button" value="<?php _e('Add Answer', 'wp-polls') ?>" onclick="create_poll_answer();" class="button" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php _e('Remove Answer', 'wp-polls') ?>" onclick="remove_poll_answer();" class="button" /></td>
			</tr>
		</tfoot>
		<tbody id="poll_answers">
		<?php
			for($i = 1; $i <= $poll_noquestion; $i++) {
				echo "<tr id=\"poll-answer-$i\">\n";
				echo "<th width=\"20%\" scope=\"row\" valign=\"top\">".sprintf(__('Answer %s', 'wp-polls'), $i)."</th>\n";
				echo "<td width=\"80%\"><input type=\"text\" size=\"50\" name=\"polla_answers[]\" /></td>\n";
				echo "</tr>\n";
				$count++;
			}
		?>
		</tbody>
	</table>
	<!-- Poll Multiple Answers -->
	<h3><?php _e('Poll Multiple Answers', 'wp-polls') ?></h3>
	<table class="form-table">
		<tr>
			<th width="40%" scope="row" valign="top"><?php _e('Allows Users To Select More Than One Answer?', 'wp-polls'); ?></th>
			<td width="60%">
				<select name="pollq_multiple_yes" id="pollq_multiple_yes" size="1" onchange="check_pollq_multiple();">
					<option value="0"><?php _e('No', 'wp-polls'); ?></option>
					<option value="1"><?php _e('Yes', 'wp-polls'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th width="40%" scope="row" valign="top"><?php _e('Maximum Number Of Selected Answers Allowed?', 'wp-polls') ?></th>
			<td width="60%">
				<select name="pollq_multiple" id="pollq_multiple" size="1" disabled="disabled">
					<?php
						for($i = 1; $i <= $poll_noquestion; $i++) {
							echo "<option value=\"$i\">$i</option>\n";
						}
					?>
				</select>
			</td>
		</tr>
	</table>
	<!-- Poll Start/End Date -->
	<h3><?php _e('Poll Start/End Date', 'wp-polls'); ?></h3>
	<table class="form-table">
		<tr>
			<th width="20%" scope="row" valign="top"><?php _e('Start Date/Time', 'wp-polls') ?></th>
			<td width="80%"><?php poll_timestamp(current_time('timestamp')); ?></td>
		</tr>
		<tr>
			<th width="20%" scope="row" valign="top"><?php _e('End Date/Time', 'wp-polls') ?></th>
			<td width="80%"><input type="checkbox" name="pollq_expiry_no" id="pollq_expiry_no" value="1" checked="checked" onclick="check_pollexpiry();" />&nbsp;&nbsp;<label for="pollq_expiry_no"><?php _e('Do NOT Expire This Poll', 'wp-polls'); ?></label><?php poll_timestamp(current_time('timestamp'), 'pollq_expiry', 'none'); ?></td>
		</tr>
	</table>
	<p style="text-align: center;"><input type="submit" name="do" value="<?php _e('Add Poll', 'wp-polls'); ?>"  class="button" />&nbsp;&nbsp;<input type="button" name="cancel" value="<?php _e('Cancel', 'wp-polls'); ?>" class="button" onclick="javascript:history.go(-1)" /></p>
</div>
</form>