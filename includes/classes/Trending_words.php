<?php


class Trending_words {
	
	private  $con;

	public function __construct (&$con) {
		$this->con = $con;
	}

	public function add_trending_group ($word, $user_id, $phrase) {

		
		$word = strip_tags($word); //removes html tags
		$word = mysqli_real_escape_string($this->con, $word);
		$word = preg_replace('/[^A-Za-z0-9\-]/', '', $word);
		$word = strtolower(str_replace(' ', '', $word));
		$phrase = strip_tags($phrase); //removes html tags
		$phrase = mysqli_real_escape_string($this->con, $phrase);
		$date = $date_added = date("Y-m-d H:i:s");

		$query = "INSERT INTO `trending_words` (`word`, `user_id`, `phrase`, `date_added`) 
			VALUES ('$word','$user_id','$phrase','$date')";

		$data = mysqli_query($this->con,$query);
	}
}

?>