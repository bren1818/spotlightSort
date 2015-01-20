<?php
include "../includes.php";
$conn = getConnection();
//css();
$listID = $_REQUEST['listID'];
$Spotlightlist = new Spotlightlist($conn);
$Spotlightlist = $Spotlightlist->load($listID);

$query = $conn->prepare("SELECT count(*) as `cnt` FROM `spotlightlistitem` WHERE `listId` = :listID");
$query->bindParam(':listID', $listID);

$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);

$count = $result['cnt'];

if( isset( $_REQUEST['ss'] ) && $_REQUEST['ss'] == 1 ){
	$useSS = 1;
}else{
	$useSS = 0;
}

?>
<link href="http://wlu.ca/css/builds/global.css" rel="stylesheet"/>


<h2>Loaded: <?php echo $Spotlightlist->getListName(); ?></h2>

<div class="row spotlights">
<div class="large-12 column">
	<div class="row-title">
		Spotlights
		<?php
			if($count > 4){
		?>	
		<a class="row-link next" data-slider="spotlights" href="#" title="View More">View More <span class="icon icon-toggleright"></span></a>
		<!--<a class="row-link prev" data-slider="spotlights" href="#" title="View More"> <span class="icon icon-toggleleft"></span></a>-->
		<?php
			}
		?>
	</div>
</div>
<div class="slider_container">
	<div class="flexslider spotlights">
		<div class="flex-viewport" style="overflow: hidden; position: relative;">
			<ul class="slides" style="">
				<?php
					$query = $conn->prepare("SELECT * FROM `spotlightlistitem` WHERE `listId` = :listID ORDER BY `itemOrder` ASC");
					$query->bindParam(':listID', $listID);
					if( $query->execute() ){
						while( $spotlight = $query->fetchObject("spotlightlistitem") ){
							$spifID = $spotlight->getSpifId();
							$spotlight = new Spotlight($conn);
							$spotlight = $spotlight->load( $spifID );
							
							if( $useSS ){
								$time = time(); //numeric value
								if( $spotlight->getSunrise() != "" && $spotlight->getSunset() != "" ){
									
									if( $spotlight->getSunrise() <= $time && $spotlight->getSunset() >= $time  ){
										renderSpotlightasLi($spotlight);
										
									}else{
										if( $spotlight->getSunrise() >= $time ){
											$spotlight->setTitle("<span style='color: #f00'>To Be Displayed in Future - </span>".$spotlight->getTitle() );
											renderSpotlightasLi($spotlight);
										}
										
										else if( $spotlight->getSunset() <= $time ){
											$spotlight->setTitle("<span style='color: #f00'>Expired - </span>".$spotlight->getTitle() );
											renderSpotlightasLi($spotlight);
										}
										
										else{
											//issue here!
											echo "<li>Time: ".$time.", Rise: ".$spotlight->getSunrise().", Set: ".$spotlight->getSunset()."</li>";
										}
									}
									
									
								}else{
									renderSpotlightasLi($spotlight);
								}
								
							}else{
								renderSpotlightasLi($spotlight);
							}
						}
					}
				?>
			</ul>
		</div>
	</div>
</div><hr class="divider"> <!-- End Slider Container -->
 
<hr class="divider">
</div>

<?php
	if($count > 4){
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<script src="http://wlu.ca/js/builds/global.js" type="text/javascript"></script>

	<?php
	}
?>

<a href="modifyList.php?listID=<?php echo $listID; ?>">Edit this List</a><br />
<a href="../index.php">API Home</a>