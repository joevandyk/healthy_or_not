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
|	- Polls Admin Javascript File													|
|	- wp-content/plugins/wp-polls/polls-admin-js.js	 						|
|																							|
+----------------------------------------------------------------+
*/


var polls_admin = new sack(polls_admin_ajax_url);
var global_poll_id = 0;
var global_poll_aid = 0;
var global_poll_aid_votes  = 0;

// Function: Delete Poll Message
function delete_poll_message() {
	document.getElementById('message').style.display = "block";
	setTimeout("remove_poll()", 1000);
}

// Function: Remove Poll From Manage Poll
function remove_poll() {
	document.getElementById("manage_polls").removeChild(document.getElementById("poll-" + global_poll_id));
}

// Function: Delete Poll
function delete_poll(poll_id, poll_confirm) {
	delete_poll_confirm = confirm(poll_confirm);
	if(delete_poll_confirm) {
		global_poll_id = poll_id;
		polls_admin.reset();
		polls_admin.setVar("do", polls_admin_text_delete_poll);
		polls_admin.setVar("pollq_id", poll_id);
		polls_admin.method = 'POST';
		polls_admin.element = 'message';
		polls_admin.onCompletion = delete_poll_message;
		polls_admin.runAJAX();
	}
}

// Function: Delete Poll Logs Message
function delete_poll_logs_message() {
	document.getElementById('message').style.display = "block";
	document.getElementById("poll_logs").innerHTML = polls_admin_text_no_poll_logs;
}

// Function: Delete Poll Logs
function delete_poll_logs(poll_confirm) {
	delete_poll_logs_confirm = confirm(poll_confirm);
	if(delete_poll_logs_confirm) {
		if(document.getElementById("delete_logs_yes").checked == true) {
			polls_admin.reset();
			polls_admin.setVar("do", polls_admin_text_delete_all_logs);
			polls_admin.setVar("delete_logs_yes", "yes");
			polls_admin.method = 'POST';
			polls_admin.element = 'message';
			polls_admin.onCompletion = delete_poll_logs_message;
			polls_admin.runAJAX();
		} else {
			alert(polls_admin_text_checkbox_delete_all_logs);
		}
	}
}

// Function: Delete Individual Poll Logs Message
function delete_this_poll_logs_message() {
	document.getElementById('message').style.display = "block";
	document.getElementById("poll_logs").innerHTML = polls_admin_text_no_poll_logs;
	document.getElementById("poll_logs_display").style.display = 'none';
	document.getElementById("poll_logs_display_none").style.display = 'block';
}

// Function: Delete Individual Poll Logs
function delete_this_poll_logs(poll_id, poll_confirm) {
	delete_poll_logs_confirm = confirm(poll_confirm);
	if(delete_poll_logs_confirm) {
		if(document.getElementById("delete_logs_yes").checked == true) {
			global_poll_id = poll_id;
			polls_admin.reset();
			polls_admin.setVar("do", polls_admin_text_delete_poll_logs);
			polls_admin.setVar("delete_logs_yes", "yes");
			polls_admin.setVar("pollq_id", poll_id);
			polls_admin.method = 'POST';
			polls_admin.element = 'message';
			polls_admin.onCompletion = delete_this_poll_logs_message;
			polls_admin.runAJAX();
		} else {
			alert(polls_admin_text_checkbox_delete_poll_logs);
		}
	}
}

// Function: Delete Poll Answer Message
function delete_poll_ans_message() {
	document.getElementById('message').style.display = "block";
	setTimeout("remove_poll_ans()", 1000);
	document.getElementById('poll_total_votes').innerHTML = (parseInt(document.getElementById('poll_total_votes').innerHTML) - parseInt(global_poll_aid_votes));
	poll_total_votes = parseInt(document.getElementById('pollq_totalvotes').value);
	poll_answer_vote = parseInt(document.getElementById("polla_votes-" + global_poll_aid).value);
	poll_total_votes_new = (poll_total_votes - poll_answer_vote);
	if(poll_total_votes_new < 0) {
		poll_total_votes_new = 0;
	}
	document.getElementById('pollq_totalvotes').value = parseInt(poll_total_votes_new);
}

// Function: Remove Poll From Manage Poll
function remove_poll_ans() {
	document.getElementById("poll_answers").removeChild(document.getElementById("poll-answer-" + global_poll_aid));
}

// Function: Delete Poll Answer
function delete_poll_ans(poll_id, poll_aid, poll_aid_vote, poll_confirm) {
	delete_poll_ans_confirm = confirm(poll_confirm);
	if(delete_poll_ans_confirm) {
		global_poll_id = poll_id;
		global_poll_aid = poll_aid;
		global_poll_aid_votes = poll_aid_vote;
		polls_admin.reset();
		polls_admin.setVar("do", polls_admin_text_delete_poll_ans);
		polls_admin.setVar("pollq_id", poll_id);
		polls_admin.setVar("polla_aid", poll_aid);
		polls_admin.method = 'POST';
		polls_admin.element = 'message';
		polls_admin.onCompletion = delete_poll_ans_message;
		polls_admin.runAJAX();
	}
}

// Function: Open Poll Message
function opening_poll_message() {
	document.getElementById('message').style.display = "block";
	document.getElementById("open_poll").style.display = "none";
	document.getElementById("close_poll").style.display = "inline";
}

// Function: Open Poll
function opening_poll(poll_id, poll_confirm) {
	open_poll_confirm = confirm(poll_confirm);
	if(open_poll_confirm) {
		global_poll_id = poll_id;
		polls_admin.reset();
		polls_admin.setVar("do", polls_admin_text_open_poll);
		polls_admin.setVar("pollq_id", poll_id);
		polls_admin.method = 'POST';
		polls_admin.element = 'message';
		polls_admin.onCompletion = opening_poll_message;
		polls_admin.runAJAX();
	}
}

// Function: Close Poll Message
function closing_poll_message() {
	document.getElementById('message').style.display = "block";
	document.getElementById("open_poll").style.display = "inline";
	document.getElementById("close_poll").style.display = "none";
}

// Function: Close Poll
function closing_poll(poll_id, poll_confirm) {
	close_poll_confirm = confirm(poll_confirm);
	if(close_poll_confirm) {
		global_poll_id = poll_id;
		polls_admin.reset();
		polls_admin.setVar("do", polls_admin_text_close_poll);
		polls_admin.setVar("pollq_id", poll_id);
		polls_admin.method = 'POST';
		polls_admin.element = 'message';
		polls_admin.onCompletion = closing_poll_message;
		polls_admin.runAJAX();
	}
}

// Function: Insert Poll Quick Tag
function insertPoll(where, myField) {
	var poll_id = prompt(polls_admin_text_enter_poll_id);
	while(isNaN(poll_id)) {
		poll_id = prompt(polls_admin_text_enter_poll_id_again);
	}
	if (poll_id > 0) {
		if(where == 'code') {
			edInsertContent(myField, '[poll id="' + poll_id + '"]');
		} else {
			return '[poll id="' + poll_id + '"]';
		}
	}
}