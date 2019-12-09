
$(function(){
	let from, to;
	from = $('.fmess').val();
	to = $('.tmess').val();
	if (from != '' && to !='') {
		append_initial_loader(from,to);	
	}
	//toggle the message resume window 
	$('.message_toggle').click((a)=>show_message_resume(a));
})


function append_initial_loader(user_from,user_to) {
	let ajax_options = {
		url: '/socialNetwork/includes/handlers/message_frame.php',
		type: 'POST',
		data: `from=${user_from}&to=${user_to}&full=true`
	}

	let ajax = $.ajax(ajax_options)
	ajax.done(message => {

		if (message){
			$('.message_group_area').append(message);
			show_text_area();
			show_last_message();
		}
	});
}



function append_message(user_from, user_to) {
	let body = $('#message_body_post').val();
	$('#message_body_post').val('');

	let ajax_options = {
		url: '/socialNetwork/includes/handlers/message_frame.php',
		type: 'POST',
		data: `from=${user_from}&to=${user_to}&body=${body}&last=true`
	}
	if (body !== '') {
		let ajax = $.ajax(ajax_options)
		ajax.done(message => {
		if (message)
			$('.message_body_component').append(message);
			show_last_message();
		});
	}
}


function show_last_message () {
	let body_content = $('.message_body_component');

	if (body_content.children().length) {
		let current = body_content.scrollTop();
		let last = body_content.children().last().offset();
		let position = current + last.top;
		body_content.scrollTop(position);
	}
}


function show_text_area() {
	$('.message_group_actions').css('display','flex');
}



function show_message_resume(a){
	$('.append_messages_pre_view').html('');
	if($('.message_resume').hasClass('open_drawer')){
		$('.message_resume').removeClass('open_drawer');
	} else {
		$('.message_resume').addClass('open_drawer');
		update_message_resume();	
	}
	$('.message_resume').slideToggle();
	
}

function update_message_resume () {
	ajax_options = {
		url: '/socialNetwork/includes/handlers/not_read_messages.php',
		type: 'POST',
	}

	let fetch = $.ajax(ajax_options);
	fetch.done((message)=>{
		if (message) {
			$('.append_messages_pre_view').html(message);
		}
	});
}



