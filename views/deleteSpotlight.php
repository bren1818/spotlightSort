<?php
include "../includes.php";
$conn = getConnection();
css();
	
	
	if(  isPostBack() ){
		$spotlightID = $_POST['id'];
		$spotlight = new Spotlight($conn);
		$spotlight = $spotlight->load( $spotlightID );
		
		if( isset($_POST['delete']) && $_POST['delete'] == "Yes" ){
			$spotlight->setDeleted( 1 );
			if( $spotlight->save() > 0 ){
				?>
					<h2>Deletion Successful</h2>
					<a href="../viewSpotlights.php">Back to Spotlight Edit List</a>
				<?php
				exit;
			}
		}else{
			?>
			<h2>Deletion Cancelled</h2>
			<a href="../viewSpotlights.php">Back to Spotlight Edit List</a>
			
			<?php
			exit;
		}
	}else{
		$spotlightID = $_REQUEST['id'];
		$spotlight = new Spotlight($conn);
		$spotlight = $spotlight->load( $spotlightID );
	}
?>
<h2>Delete Spotlight?</h2>

<div class="form">
		<form method="POST">
		<div class="row">
			<label>Type: <label><?php echo $spotlight->getType(); ?><input type="hidden" name="type" value="<?php echo $spotlight->getType() ?>" disabled="disabled"/>
		</div>
		<div class="row">
			<label>name<label><input type="text" name="name" value="<?php echo $spotlight->getName(); ?>" disabled="disabled"/>
		</div>
		<div class="row">
			<label>title<label><input type="text" name="title" value="<?php echo $spotlight->getTitle(); ?>" disabled="disabled" />
		</div>
		<div class="row">
			<label>Link<label><input type="text" name="link" value="<?php echo $spotlight->getLink(); ?>" disabled="disabled" />
		</div>
		<div class="row link-preview">
			<label>Link Preview</label><a target="_blank" href="<?php echo $spotlight->getLink(); ?>">Test Link</a>
		</div>
		<div class="row">
			<label>Image Path<label><input name="imagePath" type="text" value="<?php echo $spotlight->getImagepath(); ?>"  disabled="disabled"/>
		</div>
		<div class="row image-preview">
			<label>Image Preview: </label> <img src="<?php echo $spotlight->getImagepath(); ?>" alt="<?php echo $spotlight->getTitle(); ?>"  disabled="disabled"/>
		</div>
		<div class="row">
			<label>tags (comma separated)<label><input name="tags" type="text" value="<?php echo $spotlight->getTags(); ?>"  disabled="disabled" />
		</div>
		<div class="row">
			<label>open new window<label>
			<?php renderYesNoRadio($spotlight->getNewWindow() , "newWindow"); ?>
		</div>
		<div class="row">
			<label>sunrise<label><input name="sunrise" type="text" value="<?php echo timeStampToDate($spotlight->getSunrise()); ?>"  disabled="disabled"/>
		</div>
		<div class="row">
			<label>sunset<label><input name="sunset" type="text" value="<?php echo timeStampToDate($spotlight->getSunset()); ?>"  disabled="disabled"/>
		</div>
		<!--
		<div class="row">
			<label>deleted<label>
			<?php renderYesNoRadio($spotlight->getDeleted() , "deleted"); ?>
		</div>
		-->

		<div class="row">
			<input type="hidden" name="id" value="<?php echo $spotlight->getId(); ?>" />
			<p>Delete this: <input type="submit" name="delete" value="Yes" />
			<input type="submit" name="delete" value="No" /></p>
		</div>
		
		</form>
	</div>
	
	<hr />
	<a href="modifySpotlight.php?id=<?php echo $spotlight->getId() ?>">Edit this Spotlight</a><br />
	<a href="previewSpotlight.php?id=<?php echo $spotlight->getId() ?>">Preview this Spotlight</a><br />
	<a href="../viewSpotlights.php">Back to Spotlight Edit List</a><br />
	<a href="../index.php">API Home</a>