<?php
error_reporting(E_ALL);
	
include "spotlight.php";
include "spotlightList.php";
include "SpotlightlistItem.php";

define("SERVER_ADDRESS", "http://205.189.20.167:90/_API");

function getConnection() {
	date_default_timezone_set("America/New_York");
	
	$dbName = "cascade_spotlights"; 	//Database Name
	$dbUser = "root"; 	//Database User
	$dbPass = ""; 	//Database Password
	$dbHost = "localhost"; 	//
	
	$dbc = null;
	
	try {
		$dbc = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPass);
		$dbc->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch(PDOException $e) {
		echo "<h2>An error has occurred connecting to the database</h2>";
		echo "<p>".$e->getMessage()."</p>";
		file_put_contents('PDOErrorsLog.txt', $e->getMessage(), FILE_APPEND);
	}
	
	return $dbc;
}

	function startsWith($needle,$haystack) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
	function endsWith($needle,$haystack) {
		// search forward starting from end minus needle length characters
		return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
	}

	function isPostBack(){
      return ($_SERVER['REQUEST_METHOD'] == 'POST');
   }
   
   function pa($array){
	echo '<pre>'.print_r($array,true).'</pre>';
   }
   
   function dateToTimeStamp($date){
		if( $date == ""){
			return "";
		}else{
			return strtotime ($date) ;	
		}
	}
	
	function timeStampToDate($time){
		if( $time == ""){
		
		}else{
			return date("m/d/Y",$time);
		}
	}
   
   function renderYesNoRadio($value, $fieldName){
		if( $value == true || $value == 1 || strtolower($value) == "yes" || strtolower($value) == "true" ){
			?>
			<input type="radio" name="<?php echo $fieldName; ?>" value="1" checked>Yes
			<input type="radio" name="<?php echo $fieldName; ?>" value="0">No
			<?php
		}else{
			?>
			<input type="radio" name="<?php echo $fieldName; ?>" value="1">Yes
			<input type="radio" name="<?php echo $fieldName; ?>" value="0" checked>No
			<?php
		}
	}
	
	function renderSpotlightasLi($spotlight){
		?>
			<li style="width: 320px; float: left; display: block;"><a <?php if($spotlight->getNewWindow() ){ echo ' target="_blank" '; } ?> href="<?php echo $spotlight->getLink(); ?>" class="internal"><img alt="<?php echo $spotlight->getTitle(); ?>" src="<?php echo $spotlight->getImagePath() ?>" draggable="false"><p class="title"><?php echo $spotlight->getTitle(); ?></p><p class="optional"></p></a></li>	
		<?php
	}
   
   function css(){
   ?>
   <style>
		.form{
			width: 500px;
		}
		
		.form .row{
			width: 100%;
			clear: both;
			margin: 3px 0px;
			padding: 5px 0px;
		}
	
		label{ width: 150px;  }
		label textarea,
		label input{ float: right; width: 300px; }
		label input[type="radio"]{ float: none; width: auto; display: inline; margin: 0px 10px;}
	
	
		#listManager{ width: 1024px; margin: 0 auto; }
		
		#listManager h2{ text-align: center; }
		
		#trash ul,
		#listManager ul{ min-height: 100px; list-style: none; padding: 0px;}
		
		#trash li,
		#listManager li{ margin: 5px 0px; cursor: pointer; padding: 5px;}
		
		#listManager li:hover{
			background-color: #e1e1e1;
		}
		
		#trash,
		#currentList, #availableSpifs{
			width: 47%; margin: 0px 1%; min-height: 500px; overflow-y: auto; border: 1px solid #000;
			float: left;
		}
		
		#trash li img,
		#currentList li img, #availableSpifs li img{
			max-width: 100px;
		}
		
		#availableSpifs{ max-height: 500px; overflow-y: auto; }
		
		li.ui-sortable-helper{
			opacity: 0.4;
			filter: alpha(opacity=40); /* For IE8 and earlier */
		}
		
		.in-list{
			background-color: rgba(255,0,0,.4);
		}
		
		.clear{ clear: both; }
	</style>
   <?php
   }
?>