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
	
	$users = get_users();
	$user_number = 0;

	foreach ($users as $key){
		$meta_data = get_user_meta($key->ID);
		$result['user'.$user_number] = $meta_data['nickname'];
		$user_number++;
	}

	if(strtolower($_SERVER['HTTP_ACCEPT'] == 'application/json')){
		$result = json_encode($result);
		echo $result;
	}
	else{
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	
	die();
}

// This guy is hooking show_authors into the admin-ajax file
add_action("wp_ajax_show_authors", "show_authors");

function my_must_login(){
	$result['type'] = "you must be logged in to see the list";
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
	wp_enqueue_style('styles',  plugin_dir_url(__FILE__)."/show_authors.css");
}

add_action('wp_enqueue_scripts', 'show_authors_styles');

function render_frontend(){
	$nonce = wp_create_nonce("show_authors_nonce");
	?>

	<div 
		id="showUsers"
		data-nonce="<?php echo $nonce;?>"
		data-post_id="<?php echo $post->ID;?>"	
	>
		<button
			onclick="showUsers()"
		>show users</button>

		<ul id="userList">

		</ul>
	</div>
	<?php
}

add_shortcode('show_authors' , 'render_frontend');