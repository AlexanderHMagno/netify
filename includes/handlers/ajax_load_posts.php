<?php

include('../../config/config.php');
include('../classes/User.php');
include('../classes/Comment.php');
include('../classes/Notification.php');
include('../classes/Trending_words.php');
include('../classes/Post.php');




$limit = 10; // How many posts do we need from this page 

//$_Request is used to obtain the data that is being passed by ajax Its a type of http action, like POST or GET. I WILL TRY TO USE THE POST ACTION if I can obtain the same result.

$post = new Post($con,$_POST['userLogIn']);
echo $post->get_post($_POST,$limit);

?>