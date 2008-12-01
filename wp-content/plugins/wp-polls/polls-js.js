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
|	- Polls Javascript File															|
|	- wp-content/plugins/wp-polls/polls-js.js									|
|																							|
+----------------------------------------------------------------+
*/


// Variables
var polls = new sack(polls_ajax_url);
var poll_id = 0;
var poll_answer_id = "";
var poll_fadein_opacity = 0;
var poll_fadeout_opacity = 100;
var is_ie = (document.all && document.getElementById);
var is_moz = (!document.all && document.getElementById);
var is_opera = (navigator.userAgent.indexOf("Opera") > -1);
var is_being_voted = false;

// Function: When User Vote For Poll
function poll_vote(current_poll_id) {
	if(!is_being_voted) {
		is_being_voted = true;
		poll_id = current_poll_id;
		poll_form = document.getElementById('polls_form_' + poll_id);
		poll_answer = eval("poll_form.poll_" + poll_id);
		poll_answer_id = "";
		if(document.getElementById('poll_multiple_ans_' + poll_id)) {
			poll_multiple_ans = parseInt(document.getElementById('poll_multiple_ans_' + poll_id).value);
		} else {
			poll_multiple_ans = 0;
		}
		poll_multiple_ans_count = 0;
		if(poll_answer.length != null) {
			for(i = 0; i < poll_answer.length; i++) {
				if (poll_answer[i].checked) {					
					if(poll_multiple_ans > 0) {
						poll_answer_id = poll_answer[i].value + "," + poll_answer_id;
						poll_multiple_ans_count++;
					} else {
						poll_answer_id = parseInt(poll_answer[i].value);
					}
				}
			}
		} else {
			poll_answer_id = poll_answer.value;
		}
		if(poll_multiple_ans > 0) {
			if(poll_multiple_ans_count > 0 && poll_multiple_ans_count <= poll_multiple_ans) {
				poll_answer_id = poll_answer_id.substring(0, (poll_answer_id.length-1));
				poll_loading_text();
				poll_process();
			} else if(poll_multiple_ans_count == 0) {
				is_being_voted = false;
				alert(polls_text_valid);
			} else {
				is_being_voted = false;
				alert(polls_text_multiple + " " + poll_multiple_ans + ".");
			}
		} else {
			if(poll_answer_id > 0) {
				poll_loading_text();
				poll_process();
			} else {
				is_being_voted = false;
				alert(polls_text_valid);
			}
		}
	} else {
		alert(polls_text_wait);
	}
}

// Function: When User View Poll's Result
function poll_result(current_poll_id) {
	if(!is_being_voted) {
		is_being_voted = true;
		poll_id = current_poll_id;
		poll_loading_text();
		poll_process_result();
	} else {
		alert(polls_text_wait);
	}
}

// Function: When User View Poll's Voting Booth
function poll_booth(current_poll_id) {
	if(!is_being_voted) {
		is_being_voted = true;
		poll_id = current_poll_id;
		poll_loading_text();
		poll_process_booth();
	} else {
		alert(polls_text_wait);
	}
}

// Function: Poll Fade In Text
function poll_fadein_text() {
	if(poll_fadein_opacity == 90) {
		poll_unloading_text();
	}
	if(poll_fadein_opacity < 100) {
		poll_fadein_opacity += 10;
		if(is_opera) {
			poll_fadein_opacity = 100;
			poll_unloading_text();
		} else if(is_ie) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.filter = 'alpha(opacity=' + poll_fadein_opacity + ')';
			} else {
				poll_fadein_opacity = 100;
				poll_unloading_text();
			}
		} else	 if(is_moz) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadein_opacity/100);
			} else {
				poll_fadein_opacity = 100;
				poll_unloading_text();
			}
		}
		setTimeout("poll_fadein_text()", 100); 
	} else {
		poll_fadein_opacity = 100;
		is_being_voted = false;
	}
}

// Function: Poll Loading Text
function poll_loading_text() {
	if(poll_show_loading) {
		document.getElementById('polls-' + poll_id + '-loading').style.display = 'block';
	}
}

// Function: Poll Finish Loading Text
function poll_unloading_text() {
	if(poll_show_loading) {
		document.getElementById('polls-' + poll_id + '-loading').style.display = 'none';
	}
}

// Function: Process The Poll
function poll_process() {
	if(poll_fadeout_opacity > 0) {
		poll_fadeout_opacity -= 10;
		if(is_opera) {
			poll_fadeout_opacity = 0;
		} else if(is_ie) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.filter = 'alpha(opacity=' + poll_fadeout_opacity + ')';
			} else {
				poll_fadeout_opacity = 0;
			}
		} else if(is_moz) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadeout_opacity/100);
			} else {
				poll_fadeout_opacity = 0;
			}
		}
		setTimeout("poll_process()", 100); 
	} else {
		poll_fadeout_opacity = 0;
		polls.reset();
		polls.setVar("vote", true);
		polls.setVar("poll_id", poll_id);
		polls.setVar("poll_" + poll_id, poll_answer_id);
		polls.method = 'POST';
		polls.element = 'polls-' + poll_id + '-ans';
		polls.onCompletion = poll_fadein_text;
		polls.runAJAX();
		poll_fadein_opacity = 0;
		poll_fadeout_opacity = 100;
	}
}

// Function: Process Poll's Result
function poll_process_result() {
	if(poll_fadeout_opacity > 0) {
		poll_fadeout_opacity -= 10;
		if(is_opera) {
			poll_fadeout_opacity = 0;
		} else if(is_ie) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.filter = 'alpha(opacity=' + poll_fadeout_opacity + ')';
			} else {
				poll_fadeout_opacity = 0;
			}
		} else if(is_moz) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadeout_opacity/100);
			} else {
				poll_fadeout_opacity = 0;
			}
		}
		setTimeout("poll_process_result()", 100); 
	} else {
		poll_fadeout_opacity = 0;
		polls.reset();
		polls.setVar("pollresult", poll_id);
		polls.method = 'GET';
		polls.element = 'polls-' + poll_id + '-ans';
		polls.onCompletion = poll_fadein_text;
		polls.runAJAX();
		poll_fadein_opacity = 0;
		poll_fadeout_opacity = 100;
	}
}

// Function: Process Poll's Voting Booth
function poll_process_booth() {
	if(poll_fadeout_opacity > 0) {
		poll_fadeout_opacity -= 10;
		if(is_opera) {
			poll_fadeout_opacity = 0;
		} else if(is_ie) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.filter = 'alpha(opacity=' + poll_fadeout_opacity + ')';
			} else {
				poll_fadeout_opacity = 0;
			}
		} else if(is_moz) {
			if(poll_show_fading) {
				document.getElementById('polls-' + poll_id + '-ans').style.MozOpacity = (poll_fadeout_opacity/100);
			} else {
				poll_fadeout_opacity = 0;
			}
		}
		setTimeout("poll_process_booth()", 100); 
	} else {
		poll_fadeout_opacity = 0;
		polls.reset();
		polls.setVar("pollbooth", poll_id);
		polls.method = 'GET';
		polls.element = 'polls-' + poll_id + '-ans';
		polls.onCompletion = poll_fadein_text;
		polls.runAJAX();
		poll_fadein_opacity = 0;
		poll_fadeout_opacity = 100;
	}
}

// Function: Disable Poll's Voting Booth
function poll_disable_voting(current_poll_id){
	poll_form = document.getElementById('polls_form_' + current_poll_id);
	for(i = 0; i < poll_form.length; i++){
		poll_form[i].disabled = true;
	}
}