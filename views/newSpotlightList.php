<?php 
	include "../includes.php";
	
?>
<h1>Create a new Spotlight List</h1>

<?php
	if( isPostback() ){
		$conn = getConnection();
		$Spotlightlist = new Spotlightlist($conn);
		
		$Spotlightlist->setListName( $_POST['listName'] );
		$Spotlightlist->setDescription( $_POST['description'] );
		$Spotlightlist->setOwner( $_POST['owner'] );
		
		if( $Spotlightlist->save() > 0 ){
			echo "<h2>List Saved</h2>";
			
			echo '<a href="'.SERVER_ADDRESS.'/views/modifyList.php?listID='.$Spotlightlist->getId().'">Edit this Lists</a><br />';
			
			echo '<a href="'.SERVER_ADDRESS.'">Back to Lists</a>';
			
			exit;
		}
	}
?>

<?php if( !isPostback() ){ 
css();
?>
	<div class="form">
		<form action="newSpotlightList.php" method="POST">
		<div class="row">
			<label for="">List Name: <input name="listName" type="text" value="" /></label>
		</div>
		<div class="row">
			<label for="">Description <textarea name="description"></textarea></label>
		</div>
		<div class="row">
			<label for="">Owner <input name="owner" type="text" value="" /></label>
		</div>
		<div class="row">
			<input type="submit" value="Save" />
		</div>
		</form>
	</div>
<?php
	echo '<a href="'.SERVER_ADDRESS.'">Cancel - Back to Lists</a>';
}else{
	pa( $_POST );
}
?>