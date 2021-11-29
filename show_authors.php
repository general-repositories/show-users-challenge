<?

/*
	Plugin Name: ajax-show-authors
	Description: A truly amazing plugin.
	Version: 1.0
	Author: funcyChaos
	Author URI: https://ww.a-reilly.dev
*/

function show_authors(){
	
	if(!wp_verify_nonce($_REQUEST['nonce'], "show_authors_nonce")){
		exit("No naughty business please");
	}

	// Store users clicks, click times, and page it was clicked on

	$user_meta = get_user_meta($_REQUEST['user'], "clicks", true);
	
	$entries = ($user_meta == '') ? 0 : count($user_meta);
	
	if($entries == 0){
		$first_click[0] = array(
			'click_number'=>1,
			'click_time'=>$_REQUEST['time'],
			'page'=>$_REQUEST['post_id']
		);
		update_user_meta($_REQUEST['user'], "clicks", $first_click);
		$result = $first_click;
	}
	elseif($entries > 0){
		$clicks = $entries + 1;
		$user_meta[$entries] = array(
			'click_number'=> $clicks,
			'click_time'=>$_REQUEST['time'],
			'page'=>$_REQUEST['post_id']
		);
		update_user_meta($_REQUEST['user'], "clicks", $user_meta);
		$result = $user_meta;
	}

	wp_send_json($result);
}

// This guy is hooking show_authors into the admin-ajax file
add_action("wp_ajax_show_authors", "show_authors");

function my_must_login(){
	$result['type'] = "must login";
	$result = json_encode($result);
	echo $result;
	die();
}

add_action("wp_ajax_nopriv_show_authors", "my_must_login");

function my_script_enqueuer(){
	wp_register_script("show_authors_aj", WP_PLUGIN_URL.'/show-authors-challenge/show_authors_aj.js');
	
	// This seems like the key to the communication between ajax and jquery
	wp_localize_script('show_authors_aj', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php'))
	);

	wp_enqueue_script('show_authors_aj');
}

// Okay This is adding the javascript in as... an alternative? takes away reloads
add_action('init', 'my_script_enqueuer');

function show_authors_styles(){
	wp_enqueue_style('styles', plugin_dir_url(__FILE__)."/show_authors.css");
}

add_action('wp_enqueue_scripts', 'show_authors_styles');

function render_frontend(){
	?>
		<div 
			id="showUsers"
			data-nonce="<?php echo wp_create_nonce("show_authors_nonce");?>"
			post-id="<?php echo get_the_ID();?>"
			user="<?php echo get_current_user_id();?>"
		>
			<button
				onclick="showUsers()"
			>show users</button>

			<ul id="userList">

			</ul>
		</div>
	<?php
}

add_shortcode('show_authors', 'render_frontend');