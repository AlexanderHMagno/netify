<?php 

// class to be used thru the whole app
// function to check in a 

class Arc {

    protected $con;

	public function __construct(&$con=null){
        $this->con = $con;
	}

	public function in_array_r($needle, $haystack, $strict = false) {
    	foreach ($haystack as $item) {
    	    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        	}
   		}	

    return false;
	}

    public function purge_value ($value) {
        $value = strip_tags($value);
        if ($this->con != null) {
            $value = mysqli_real_escape_string($this->con,$value);
        }
        return $value;
    }
}




?>