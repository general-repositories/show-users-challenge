document.addEventListener('DOMContentLoaded', ()=>{
	const voteLink = document.getElementById('vote_link');
	
	voteLink.addEventListener('click', (event)=>{
		// Don't do the php thing. Stay Here yo
		event.preventDefault();

		// Get the current stuffs from the things
		const post_id = voteLink.getAttribute('data-post_id');
		const nonce = voteLink.getAttribute('data-nonce');
		
		// Encode the damn thangs
		const data = encodeURI(`action=my_user_vote&post_id=${post_id}&nonce=${nonce}`)

		fetch(myAjax.ajaxurl, {
			method: 'POST',
			headers: {
				'Accept': 'application/json, text/javascript, */*; q=0.01',
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				'X-Requested-With': 'XMLHttpRequest'
			},
			body: data
		})
		.then(res=>res.json())
		.then(data=>{
			console.log(data);
			document.getElementById('vote_counter').innerHTML = data.vote_count;
		});
	});
});