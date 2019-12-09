


$('#friends_search').on('input',(data)=>{
	let search = $(data.target).val();
	let ajax_options = {
		url: "http://localhost:8080/socialNetwork/includes/handlers/friends_live_search.php",
		type: 'POST',
		data: `search=${search}`
	}
	$('.result_friend_search').html('');
	let friends = $.ajax(ajax_options);

	friends.done((message)=>{
		if (search){
			$('.result_friend_search').html(message);	
		}
		
	});
})

$('.option_friend_selector>li').on('click',(data)=>{
	let selected = $(data.target).text();
	$('.option_friend_selector').find('.list-group-item').removeClass('selected');
	$(data.target).addClass('selected');
	if (selected === 'Friend') {
		$('.friends_confirmed').show();
		$('.friends_search').hide();
	} else {
		$('.friends_confirmed').hide();
		$('.friends_search').show();
	}
})