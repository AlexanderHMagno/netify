


function request_friendship (userLoggedIn, userTo){

	// run ajax 
	//change the text to pending to accept

	let ajax_options = {
		url : "/socialNetwork/includes/handlers/update_friendship_status.php",
		type: 'POST',
		data: `status=request&userLoggedIn=${userLoggedIn}&userTo=${userTo}`
	}

	let data = $.ajax(ajax_options);
	data.done(message => {

		$('#friendship_status').html("<img id='friendship_status' class='logo_icon' src='/socialNetwork/assets/images/friendship/pending.png' alt='pending Friendship' title='Pending Friendship'>")
	});


}

function action_friendship (userLoggedIn, userTo,action,trigger){
console.log(trigger)
	// run ajax 
	//change the text to pending to accept

	let ajax_options = {
		url : "/socialNetwork/includes/handlers/update_friendship_status.php",
		type: 'POST',
		data: `status=update&userLoggedIn=${userLoggedIn}&userTo=${userTo}&action=${action}`
	}


	let data = $.ajax(ajax_options);
	data.done(message => $(trigger).parents('.individual_friend_request').remove());
}


function remove_friendship (userLoggedIn, userTo) {
	// run ajax 
	//change the text to add as a friend

	let ajax_options = {
		url : "/socialNetwork/includes/handlers/update_friendship_status.php",
		type: 'POST',
		data: `status=remove&userLoggedIn=${userLoggedIn}&userTo=${userTo}`
	}


	let data = $.ajax(ajax_options);
	data.done(message => {
		let add_friend = `<a id='friendship_status' onClick='request_friendship(${userLoggedIn},${userTo})'><img 
                  class='logo_icon' src='/socialNetwork/assets/images/friendship/unfriend.png' alt='Add as a friend' title='Add as a friend'></a>`;
		$('#friendship_status').html(add_friend);
	});

}