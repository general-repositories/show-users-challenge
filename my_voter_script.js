document.addEventListener('DOMContentLoaded', ()=>{
	const voteLink = document.getElementById('vote_link');
	
	voteLink.addEventListener('click', (event)=>{
		// Don't do the php thing. Stay Here yo
		event.preventDefault();

		// Get the current stuffs from the things
		const post_id = voteLink.getAttribute('data-post_id');
		const nonce = voteLink.getAttribute('data-nonce');

		fetchAjax(myAjax.ajaxurl, {
			'action': 'my_user_vote',
			'post_id': voteLink.getAttribute('data-post_id'),
			'nonce': voteLink.getAttribute('data-nonce')
		}).then(object=>{
			console.log(object);
			document.getElementById('vote_counter').innerHTML = object.vote_count;
		});
	});
});


// Dynamic AJAX Fetch Function lolies
async function fetchAjax(url, body){
	let bodyString = ``;
	let overOne = 1;
	
	for(const key in body){
		overOne === 1 ? 
			bodyString = bodyString + `${key}=${body[key]}` :
			bodyString = bodyString + `&${key}=${body[key]}`;

		overOne++;
	}
	
	const encodedBody = encodeURI(bodyString);

	const response = fetch(myAjax.ajaxurl, {
		method: 'POST',
		headers: {
			'Accept': 'application/json, text/javascript, */*; q=0.01',
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
			'X-Requested-With': 'XMLHttpRequest'
		},
		body: encodedBody
	});

	const data = (await response).json();

	const object = await data;

	return object;
}