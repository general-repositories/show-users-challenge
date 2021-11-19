let isRan = false;

function showUsers(){
	const userList = document.getElementById('userList');
	const div = document.getElementById('showUsers');

	if(!isRan){
		isRan = true;
		fetchAjax(myAjax.ajaxurl, {
			'action': 'show_authors',
			'post_id': div.getAttribute('data-post_id'),
			'nonce': div.getAttribute('data-nonce')
		})
		.then(object=>{
			for (const key in object){
				const element = document.createElement('li');
				element.innerText = object[key];
				userList.appendChild(element);
			}
		});
	}
}

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

	const response = fetch(url, {
		method: 'POST',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
		},
		body: encodedBody
	});

	const data = (await response).json();

	const object = await data;

	return object;
}