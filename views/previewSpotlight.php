<?php
include "../includes.php";
$conn = getConnection();
css();
$spotlightID = $_REQUEST['id'];
$spotlight = new Spotlight($conn);
$spotlight = $spotlight->load( $spotlightID );
?>
<link href="http://wlu.ca/css/builds/global.css" rel="stylesheet"/>

<h2>Loaded: <?php echo $spotlight->getTitle(); ?></h2>

<div class="row spotlights">
<div class="large-12 column">
	<div class="row-title">
		Spotlights
		<!--
		<a class="row-link next" data-slider="spotlights" href="#" title="View More">View More <span class="icon icon-toggleright"></span></a>
		<a class="row-link prev" data-slider="spotlights" href="#" title="View More"> <span class="icon icon-toggleleft"></span></a>
		-->
	</div>
</div>
<div class="slider_container">
	<div class="flexslider spotlights">
		<div class="flex-viewport" style="overflow: hidden; position: relative;">
			<ul class="slides" style="width: 2600%; margin-left: 0px;">
				<?php
				renderSpotlightasLi($spotlight);
				?>
			</ul>
		</div>
	</div>
</div><hr class="divider"> <!-- End Slider Container -->
 
<hr class="divider">
</div>

<a href="modifySpotlight.php?id=<?php echo $spotlight->getId() ?>">Edit this Spotlight</a> <br />
<a href="deleteSpotlight.php?id=<?php echo $spotlight->getId() ?>">Delete this Spotlight</a> <br />
<a href="../viewSpotlights.php">Back to Spotlight Edit List</a><br />
<a href="../index.php">API Home</a>