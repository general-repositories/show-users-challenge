let isRan = false;

function showUsers(){
	const userList = document.getElementById('userList');
	const div = document.getElementById('showUsers');
	const today = new Date();
	const dateString = `${today.getHours()}.${today.getMinutes()}.${today.getSeconds()}`;

	fetch(myAjax.ajaxurl, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		},
		body: `action=show_authors&nonce=${div.getAttribute('data-nonce')}&post_id=${div.getAttribute('post-id')}&user=${div.getAttribute('user')}&time=${dateString}`
	})
	.then(res=>res.json())
	.then(array=>{
		if(array.type != 'must login'){
			if(!isRan){
				isRan = true;
				for(const key in array){
					const element = document.createElement('li');
					element.innerText = `Clicked ${array[key].click_number} times, click at ${array[key].click_time}`;
					userList.appendChild(element);
				}
			}
			else{
				const element = document.createElement('li');
				element.innerText = `Clicked ${array[array.length - 1].click_number} times, click at ${array[array.length - 1].click_time}`;
				userList.appendChild(element);
			}
		}
		else alert('you must be logged in to see the list');
	});
}