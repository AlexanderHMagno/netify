$(function() {
	load_first_post();
	changeBackgroundColor();
});



// this function is used to show the post in the main screen
//@param {integer} numberOfPage is the page that we want to show.
//@param {integer} userLogIn the user that we currently have. 
function load_posts (numberOfPage,userLogIn,post_id=null) {

 	let data = `page=${numberOfPage}&userLogIn=${userLogIn}`;
 	if (post_id != null) {
 		data = `page=${numberOfPage}&userLogIn=${userLogIn}&post_id=${post_id}`;
 	}

	return $.ajax({
			url:"http://localhost:8080/socialNetwork/includes/handlers/ajax_load_posts.php",
			type:"POST",
			data:data,
			cache: false,
			})	
}

//This function will bre trigger as soon the document is ready, it will sent an ajax request and retrieve
// the first 10 posts.
//@param NONE
function load_first_post (){
	//unique id
	let post_id = $('.post_unique_id').val();

	if (post_id) {
		let user = $('.user_Logged_In').val();
		if (user) {
			let data = load_posts(1,user,post_id);
			data.done((message)=> {
				$('.post_group_area').html(message);
			})
		}	
	} else {
	//multiple_ids
		let user = $('.user_id').val();
		if (user) {
			let data = load_posts(1,user);
			data.done((message)=> {
				$('.post_group_area').html(message);
				$('#loading').hide();
			})
		}	
	}
}
	
// This function will be called to post the new post once the user hits the last part of the page.
// @param NONE
function load_secondary_post() {

	let post_id = $('.post_unique_id').val();

	if (!post_id.length) {

		if (!$('.post_group_area').hasClass('loading_post')){
			if($('.no_more_post').val() === 'false'){

				$('.post_group_area').addClass('loading_post');
				let page = parseInt($('.next_page').val());
				let user = parseInt($('.user_id').val());
				$('#loading').show();
				let add_posts = load_posts(page,user);

				add_posts.done(message=>{
					$('.post_group_area').removeClass('loading_post');
					$('.next_page').remove();
					$('.no_more_post').remove();
					$('.post_group_area').append(message);
					$('#loading').hide();
				})
			} else {
				$('.post_group_area').append('<p class="no_more_post_to_show"> No More Post to Show</p>');
			}
		}

	}
}


//This function will run every time the user moves the scroll is checking if the user touch the bottom 
// of the document, if the user hits the bottom it will trigger the function load_secondary_post_which


$(window).scroll(()=>{
	let user = $('.user_id').val();
	if (user){
		if( $(document).height() - window.innerHeight - $(document).scrollTop() < 10 ) {
			if ($('.no_more_post_to_show').length === 0 ) {
				load_secondary_post();
			}
		}
	}
})


function changeBackgroundColor () {

	$('body').addClass('welcomeUser');
}