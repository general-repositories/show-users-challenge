<?

/*
	Plugin Name: ajax-show-authors
	Description: A truly amazing plugin.
	Version: 1.0
	Author: funcyChaos
	Author URI: https://ww.a-reilly.dev
*/

function my_user_vote(){
	
	if(!wp_verify_nonce($_REQUEST['nonce'], "my_user_vote_nonce")){
		exit("No naughty business please");
	}
	
	$vote_count = get_post_meta($_REQUEST["post_id"], "votes", true);
	$vote_count = ($vote_count == ’) ? 0 : $vote_count;
	$new_vote_count = ++$vote_count;

	$vote = update_post_meta($_REQUEST["post_id"], "votes", $new_vote_count);

	if($vote === false){
		$result['type'] = "error";
		$result['vote_count'] = $vote_count;
	}
	else{
		$result['type'] = "success";
		$result['vote_count'] = $new_vote_count;
	}

	if(strtolower($_SERVER['HTTP_ACCEPT'] == 'application/json')){
		$result['server'] = $_SERVER;
		$result['request'] = $_REQUEST;
		$result = json_encode($result);
		echo $result;
	}
	else{
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	
	die();
}

// This guy is hooking my_user_vote into the admin-ajax file
add_action("wp_ajax_my_user_vote", "my_user_vote");

function my_must_login(){
	$result['type'] = "error";
	$result = json_encode($result);
	echo $result;
	die();
}

add_action("wp_ajax_nopriv_my_user_vote", "my_must_login");


function my_script_enqueuer(){
	wp_register_script("my_voter_script", WP_PLUGIN_URL.'/javascript-wordpress-ajax-call/my_voter_script.js');
	
	// This seems like the key to the communication between ajax and jquery
	wp_localize_script('my_voter_script', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php'))
	);

	wp_enqueue_script('my_voter_script');
}

// Okay This is adding the javascript in as... an alternative? takes away reloads
add_action('init', 'my_script_enqueuer');
