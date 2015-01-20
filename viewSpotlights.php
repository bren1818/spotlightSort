<?php
	include "includes.php";
	$conn = getConnection();
	css();
?>
<style>
	li.spotlight{
		width: 200px;
		float: left;
		display: block;
		clear: both;
		margin: 10px 0px;
		border: 1px solid #000;
		padding: 10px;
	}
	
	li.spotlight img{
		width: 125px;
		height: 125px;
	}
</style>
<div id="availableSpotlights">
	<ul class="spotlights">
		<?php
			$query = $conn->prepare("SELECT * FROM `spotlight` WHERE `deleted` = 0 ORDER BY `lastModified` DESC ");
			if( $query->execute() ){
				while( $spotlight = $query->fetchObject("spotlight") ){
					echo '<li class="spotlight">
							<div>
								<div class="title"><b>Title:</b> '.$spotlight->getTitle().'</div>
								<div class="image"><b>Image:</b> <img src="'.$spotlight->getImagePath().'" /></div>
								<div class="tags"><b>Tags:</b> '.$spotlight->getTags().'</div>
								<div class="sunrise"><b>Sunrise:</b> '.timeStampToDate($spotlight->getSunrise()).'</div>
								<div class="sunset"><b>Sunset:</b> '.timeStampToDate($spotlight->getSunset()).'</div>
								<div class="tools"><a href="views/deleteSpotlight.php?id='.$spotlight->getId().'">Delete</a> <a href="views/modifySpotlight.php?id='.$spotlight->getId().'">Modify</a> <a href="views/previewSpotlight.php?id='.$spotlight->getId().'">Preview</a></div>
								<input type="hidden" name="spotlightID" value="'.$spotlight->getId().'" />
							</div>
						</li>';
				}
			}
		?>
	</ul>
</div>