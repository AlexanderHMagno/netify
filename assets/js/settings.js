




$('.update_settings').click(element => {

	let action = element.currentTarget.id;
	let parent = element.target.parentElement;
	let user_id = $('.user_id').val();
	let data = $(parent).find('input').serialize()
	
	let object_checker = create_object(data);
	let evaluated_reason = '';

	switch (action) {
		case 'settings_name_general':
			 evaluated_reason = check_restrictions(object_checker);
			 $(`.group-${action}`).remove();
			 if(evaluated_reason.includes(0)) {
			 	append_element('settings_first_name','Name is too short',action);
			 } 
			 if (evaluated_reason.includes(1)) {
			 	append_element('settings_last_name','Last name too short',action);
			 }
		break;
		case 'settings_email_general':
			evaluated_reason = check_restrictions(object_checker);
			$(`.group-${action}`).remove();
		 	if(evaluated_reason.includes(0)) {
			 	append_element('new_email','Email needs more info',action);
	 		} 
		break;
		case 'settings_password_general':
			evaluated_reason = check_password(object_checker);
			evaluated_reason = evaluated_reason.concat(check_restrictions(object_checker));
			$(`.group-${action}`).remove();
			if (evaluated_reason.includes('match')){
				append_element('newpassword1','password doesn\'t match',action);
				append_element('newpassword2','password doesn\'t match',action);
			}
			if(evaluated_reason.includes(0)) {
			 	append_element('oldpassword1','Password is too short',action);
	 		} 
		 	if (evaluated_reason.includes(1)) {
			 	append_element('newpassword1','Password is too short',action);
		 	}
		 	if (evaluated_reason.includes(2)) {
			 	append_element('newpassword2','Password is too short',action);
		 	}

		break;
		case 'settings_close_account':
			evaluated_reason = [];
		break;
	}

if (!evaluated_reason.length && evaluated_reason !== '') {
	
	data = `${data}&id=${user_id}&action=${action}`;
	let ajax_options = {
		url: '/socialNetwork/includes/handlers/settings.php',
		type: 'POST',
		data: data
	}

	if (!$(`#${action}`).hasClass('processing_order')) {
		let ajax = $.ajax(ajax_options);
		$(`#${action}`).addClass('processing_order');
		ajax.done(message => {
			console.log(typeof message);
			console.log(message);
			message = JSON.parse(message);
			if (message.respond == 1) {
				let fn = window[message.data.action];
				if(typeof fn == 'function') fn(message.data);
				confirm_update(message.data);
			} else {
				problem_has_been_detected(message);
			}
		})
	}
}
	
	
	
});

//create object from a serialize string
function create_object (data) {

	let parm = data.split('&');
	let parm_obj = {};

	parm.forEach(element => {
		let obj = element.split('=');
		parm_obj[obj[0]] = obj[1];
	});

	return parm_obj;
}


//evaluate the restriction, now just evaluating the lenght
function check_restrictions (data) {
	let values = Object.values(data);
	let error_array = [];

	values.forEach((value,index) => {
		if ( value.length < 3) {
			error_array.push(index)
		}
	})
	return error_array;
}

function check_password (data) {
	let error_array = [];
	//password not equal

	if (data.newpassword1 != data.newpassword2) {
		error_array.push('match');
	}
	return error_array;
}

//if the restrriction founds a problem we can add an element to notify the user 
function append_element (obj,text, group) {

	let element = `<span class="wrong_information group-${group}">${text}</span>`;
	$(`#${obj}`).after(element);
}


//when we receive the data from php what we would like to do, this is gonna be a series of different functions
function settings_name_general (data) {
	
	$('.profile_user_name').html(data.first);
	
} 


function confirm_update (data) {
	
	let current_text = $(`#${data.action}`).text();
	$(`#${data.action}`).text(`${current_text} (info has been updated)`);
	setInterval(()=> {
		$(`#${data.action}`).text(`${current_text}`).removeClass('processing_order');
	},3000);

}


function problem_has_been_detected (message) {
console.log(message);
	switch (message.data.action) {
		case 'settings_password_general':
			append_element('oldpassword1',message.respond,message.data.action);
			$('#oldpassword1').val('');
			$('#newpassword1').val('');
			$('#newpassword2').val('');
		break;
		case 'settings_email_general':
			 	append_element('new_email',message.respond, message.data.action);
		break;
		case 'settings_close_account':
			let current_tab = window.location;
			window.location.replace(current_tab);
		break;
	}

	$(`#${message.data.action}`).removeClass('processing_order');
}
