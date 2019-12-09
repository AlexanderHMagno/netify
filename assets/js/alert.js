$(function() {
    	//toggle the message resume window 
	$('.alert_toggle').click(() => show_alert_resume());
});

function show_alert_resume(){
	$('.append_alert_pre_view').html('');
	if($('.alert_resume').hasClass('open_drawer')){
		$('.alert_resume').removeClass('open_drawer');
	} else {
		$('.alert_resume').addClass('open_drawer');
		update_alert_resume();	
	}
	$('.alert_resume').slideToggle();
}


function update_alert_resume () {
	ajax_options = {
		url: '/socialNetwork/includes/handlers/not_read_alert.php',
		type: 'POST',
	}

	let fetch = $.ajax(ajax_options);
	fetch.done((message)=>{
		if (message) {
			$('.append_alert_pre_view').html(message);
		}
	});
}