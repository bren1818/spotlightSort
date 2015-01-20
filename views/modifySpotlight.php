<?php
	include "../includes.php";
	$conn = getConnection();
	css();
	
	
	if(  isPostBack() ){
		//pa ( $_POST );
		$spotlightID = $_POST['id'];
		$spotlight = new Spotlight($conn);
		$spotlight = $spotlight->load( $spotlightID );
		if( !isset($_POST['type']) ){
			$spotlight->setType("block");
		}else{
			$spotlight->setType( $_POST['type'] );
		}
		$spotlight->setName( $_POST['name'] );
		$spotlight->setTitle( $_POST['title'] );
		//$spotlight->setPublished( $_POST[''] );
		$spotlight->setImagePath( $_POST['imagePath'] );
		$spotlight->setLink( $_POST['link'] );
		$spotlight->setNewWindow( $_POST['newWindow'] );
		$spotlight->setLastModified( time()  );
		$spotlight->setTags( $_POST['tags'] );
		//$spotlight->setDeleted( $_POST['deleted'] );
		$spotlight->setSunrise( dateToTimeStamp($_POST['sunrise']) );
		$spotlight->setSunset( dateToTimeStamp($_POST['sunset']) );
		
		//dateToDate( $_POST['sunrise'] );
		
		if( $spotlight->save() > 0 ){
			echo "Saved!";
		}
		
	}else{
		$spotlightID = $_REQUEST['id'];
		$spotlight = new Spotlight($conn);
		$spotlight = $spotlight->load( $spotlightID );
		
		if( startsWith("/",$spotlight->getLink() ) ){
			$spotlight->setLink( "http://wlu.ca".$spotlight->getLink() );
		}
	}
	
	
	
	
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<script>
		$(function(){
			$('input[name="sunrise"]').datepicker();
			$('input[name="sunset"]').datepicker();
		});
	</script>	
	
	<div class="form">
		<form method="POST">
		<div class="row">
			<label>Type: <label><?php echo $spotlight->getType(); ?><input type="hidden" name="type" value="<?php echo $spotlight->getType() ?>" />
		</div>
		<div class="row">
			<label>name<label><input type="text" name="name" value='<?php echo $spotlight->getName(); ?>' />
		</div>
		<div class="row">
			<label>title<label><input type="text" name="title" value='<?php echo $spotlight->getTitle(); ?>' />
		</div>
		<div class="row">
			<label>Link<label><input type="text" name="link" value="<?php echo $spotlight->getLink(); ?>" />
		</div>
		<div class="row link-preview">
			<label>Link Preview</label><a target="_blank" href="<?php echo $spotlight->getLink(); ?>">Test Link</a>
		</div>
		<div class="row">
			<label>Image Path<label><input name="imagePath" type="text" value="<?php echo $spotlight->getImagepath(); ?>" />
		</div>
		<div class="row image-preview">
			<label>Image Preview: </label> <img src="<?php echo $spotlight->getImagepath(); ?>" alt="<?php echo $spotlight->getTitle(); ?>" />
		</div>
		<div class="row">
			<label>tags (comma separated)<label><input name="tags" type="text" value="<?php echo $spotlight->getTags(); ?>" />
		</div>
		<div class="row">
			<label>open new window<label>
			<?php renderYesNoRadio($spotlight->getNewWindow() , "newWindow"); ?>
		</div>
		<div class="row">
			<label>sunrise<label><input name="sunrise" type="text" value="<?php echo timeStampToDate($spotlight->getSunrise()); ?>" />
		</div>
		<div class="row">
			<label>sunset<label><input name="sunset" type="text" value="<?php echo timeStampToDate($spotlight->getSunset()); ?>" />
		</div>
		<!--
		<div class="row">
			<label>deleted<label>
			<?php renderYesNoRadio($spotlight->getDeleted() , "deleted"); ?>
		</div>
		-->
		<div class="row">
			<input type="hidden" name="id" value="<?php echo $spotlight->getId(); ?>" />
			<input type="submit" value="Save" />
		</div>
		
		</form>
	</div>
	
	<hr />
	
	<a href="../viewSpotlights.php">Back to Spotlight Edit List</a><br />
	<a href="previewSpotlight.php?id=<?php echo $spotlight->getId() ?>">Preview this Spotlight</a><br />
	<a href="deleteSpotlight.php?id=<?php echo $spotlight->getId() ?>">Delete this Spotlight</a> <br />
	<a href="../index.php">API Home</a>
	
<?php	
	//pa( $spotlight );
?>