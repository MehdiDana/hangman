<?php 
// generate a randon word and use word.php  

require_once("word.php");

// set flag
//$max_guess = 10; 

$read_dic = file_get_contents("words_british.txt");	// read from a big dictionary (linux dictionary of british word)
$dictionary = explode("\n", $read_dic);

//$dictionary = array ("love", "god", "sex", "code", "world", "hangman", "right", "left", "good");

$size_of_dic = count($dictionary);

if(!isset($_SESSION['word_id'])){
	$r_id = rand(0, $size_of_dic-1);
	$_SESSION['word_id'] = 	$r_id ;
	$_SESSION['number_wong_guess']= 0;
	$_SESSION['urguesses']= array();
}else {
	$r_id = $_SESSION['word_id'];
}

$gword = new word($dictionary[$r_id]);
$gword->set_id($r_id);
$word_id = $gword->get_id();
$max_guess  = $gword->get_max_guess();
$gword_name= strtoupper($gword->get_name());
// word is ready to be procced

$word_size = strlen($gword->get_name());
$word_chars = str_split($gword_name);
$word_char_unique = array_unique($word_chars);
$result = 0;
$ll=0;

if(isset($_POST['guess_it'])){
	$urguess = $_POST['guess'];
	array_push($_SESSION['urguesses'], $urguess);
	$guessed_char = $_SESSION['urguesses'];
	if(!in_array($urguess, $word_chars)){
		$_SESSION['number_wong_guess']++;
	}
	$result = check_if_won();
	$ll = check_life_bar();
}

function check_if_won(){
	global $word_char_unique;
//	global $word_size;
	global $max_guess;
	$result = array_intersect($_SESSION['urguesses'], $word_char_unique);
	if(count($result)== count($word_char_unique)){
			return $result = 1;	
	}
	if($_SESSION['number_wong_guess']< $max_guess ){
		$ll=$_SESSION['number_wong_guess']*10;
		return $result =0;
	}else{
		return $result = -1;
	}
}

function check_life_bar(){
	global $max_guess;
	if($_SESSION['number_wong_guess']< $max_guess ){
		$ll=$_SESSION['number_wong_guess']*10;
		return $ll;
	}else {
		return 100;
	}
}


?>
