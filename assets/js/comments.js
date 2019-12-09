
$(function(){
	$('textarea').on('input', search_at);
})

// This function is used to show the post in the main screen
//@param {integer} post_id number of hte post where we are going to commit
//@param {integer} user_id user who comment
function append_comments_form (post_id,user_id) {

	if (!$(`#body${post_id}`).length) {
		let ajax, ajax_options,selector;

		ajax_options = {
			url : '/socialNetwork/includes/handlers/comment_frame.php',
			type : 'POST',
			data : `post_id=${post_id}&user_id=${user_id}`,
		};

		ajax = $.ajax(ajax_options);
		ajax.done((message) => {
			selector = `#parent_comment${post_id}`; 
			$(selector).find('.individual_post_body').append(message);
		});
	} else {
		close_comment(post_id);
	}
}

function close_comment (post_id) {
	let comment_group = `#comment_group${post_id}`;
	$(comment_group).remove();
}

function delete_post (post_id) {

	let ajax_options, ajax;
	ajax_options = {
		url:'/socialNetwork/includes/handlers/delete_comment.php',
		type:'POST',
		data: `delete=yes&post_id=${post_id}`
	}
	ajax = $.ajax(ajax_options);
	ajax.done(message => {
		if (message >0) 
			$(`#parent_comment${post_id}`).slideUp();
	});
}



function post_comment (post_id, userLogIn) {
	let ajax, ajax_options;
	let user_to = $('.user_id').val();
	let body = $(`#body${post_id}`).val();
	let body_length = body.replace(/\s/g,'').length;


	if (body_length > 1) {
		ajax_options = {
			url : '/socialNetwork/includes/handlers/comment_frame.php',
			type : 'POST',
			data : `post_id=${post_id}&user_to=${user_to}&userLogIn=${userLogIn}&body=${body}`
		}

		ajax = $.ajax(ajax_options);
		ajax.done(message => {
			let updatenumber = $(`#CommentNumber${post_id}`);
			let numbercomments = parseInt(updatenumber.text());
			updatenumber.text(numbercomments+1);
			selector = `.subcomments_group${post_id}`;
			$(`#body${post_id}`).val(''); 
			$(selector).prepend(message);
		});
	} else {
		console.log(body_length)
	}
}



function update_like_post (post_id) {
	let userLogIn = parseInt($('.user_Logged_In').val());
	//if we only have on post 
		let unique_post = $('.post_unique_id');
		if (unique_post.length) {
			userLogIn = 'unique_post';
		}

	if (isNaN(userLogIn)) {
		userLogIn = parseInt($('.user_id').val());
	}
	
	let trigger_option = $(`#likes${post_id}`).text();
	let likes = $(`#likesNumber${post_id}`);
	let number_likes = parseInt(likes.text());

	let ajax_options = {
		url: '/socialNetwork/includes/handlers/like_frame.php',
		type: 'POST',
		data: `type=${trigger_option}&post_id=${post_id}&userLogIn=${userLogIn}`
	}

	let ajax = $.ajax(ajax_options);
	ajax.done(message =>{
		if(typeof message === "string"){
			message = JSON.parse(message);
			$(`#likes${post_id}`).text(message['trigger']);
			
			if (message['trigger'] === 'Like') {
				likes.text(number_likes-1);
				likes.parent().removeClass('redColor');

			} else {
				likes.text(number_likes+1);
				likes.parent().addClass('redColor');
			} 
		}
	})
}


function search_at(x){
	// console.log(x.target.value)
	let information = x.target.value;
	let at_regex = /#[a-z]+/g;

	if (at_regex.test(information)){

		let at_replace = [...information.match(at_regex)];
		if (at_replace[0].length > 3) {
			let ajax_options = {
				url: '/socialNetwork/includes/handlers/at_friends.php',
				type: 'POST',
				data: `at_replace=${at_replace}`
			}

			let ajax = $.ajax(ajax_options);

			ajax.done((message) => {
				message = JSON.parse(message);
				if (message.length) {
					information = information.replace(at_replace[0],message[0]);
					x.target.value = information;
				}
			});
		}

	}

}