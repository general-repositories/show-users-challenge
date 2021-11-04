document.addEventListener('DOMContentLoaded', ()=>{
	const voteLink = document.getElementById('vote_link');
	
	voteLink.addEventListener('click', (event)=>{
		// Don't do the php thing. Stay Here yo
		event.preventDefault();

		// Get the current stuffs from the things
		const post_id = voteLink.getAttribute('data-post_id');
		const nonce = voteLink.getAttribute('data-nonce');
		
		// Make a new outdated ass request from the classy class
		let ajaxReq = new XMLHttpRequest();
		
		// Be prepared to make the call down below. Basically an event listener lol
		ajaxReq.onreadystatechange = ()=>{
			// Check and see if this worked. 1 2 3 4
			if(ajaxReq.readyState === 4 && ajaxReq.status === 200){
				// It Worked we got it baby!
				const response = JSON.parse(ajaxReq.response);

				console.log(response);

				// Change the damn thing
				document.getElementById('vote_counter').innerHTML = response.vote_count;
			}
		}
		
		// Setup the HTTP request
		ajaxReq.open('POST', myAjax.ajaxurl, true);

		// Get all the headers right. ExActLy Right Or ELSE
		ajaxReq.setRequestHeader("Accept", "application/json, text/javascript, */*; q=0.01");
		
		ajaxReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");

		ajaxReq.setRequestHeader("X-Requested-With", "XMLHttpRequest");

		// Encode the damn thangs
		const data = encodeURI(`action=my_user_vote&post_id=${post_id}&nonce=${nonce}`)

		// Send the request With the encoded form body from the previous line
		ajaxReq.send(data);
	});
});