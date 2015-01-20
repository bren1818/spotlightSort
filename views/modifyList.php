<?php
	include "../includes.php";
	$listID = $_REQUEST['listID'];
	$conn = getConnection();
	$Spotlightlist = new Spotlightlist($conn);
	//$Spotlightlist->setId(  );
	$Spotlightlist = $Spotlightlist->load($listID);
	
	function renderToolSet($spotlight){
		ob_start();
		?>
		<p><a href="previewSpotlight.php?id=<?php echo $spotlight->getId(); ?>" target="_blank">Preview</a>
		<a href="modifySpotlight.php?id=<?php echo $spotlight->getId(); ?>" target="_blank">Edit</a>
		<a href="deleteSpotlight.php?id=<?php echo $spotlight->getId(); ?>">Delete</a>
		<?php
			if( $spotlight->getSunrise() != ""){
				echo "<b>Sunrise: </b>".timeStampToDate($spotlight->getSunrise());
			}
			
			if( $spotlight->getSunset() != ""){
				echo "<b>Sunset: </b>".timeStampToDate($spotlight->getSunset() );
			}

			if( $spotlight->getDeleted() ){
				echo "<b>Deleted</b>";
			}
		echo '</p>';
		$tools = ob_get_contents();
		 ob_end_clean();	
return $tools;		 
	}
	
	if( $Spotlightlist->getId() > 0 ){
		?>
			<div class="form">
				<form action="newSpotlightList.php" method="POST">
				<div class="row">
					<label for="">List Name: <input name="listName" type="text" value="<?php echo $Spotlightlist->getListName(); ?>" /></label>
				</div>
				<div class="row">
					<label for="">Description <textarea name="description"><?php echo $Spotlightlist->getDescription(); ?></textarea></label>
				</div>
				<div class="row">
					<label for="">Owner <input name="owner" type="text" value="<?php echo $Spotlightlist->getOwner(); ?>" /></label>
				</div>
				<div class="row">
					<!--<input type="submit" value="Save" />-->
					<label for=""><a target="_blank" href="<?php echo SERVER_ADDRESS."/feed.php?feed=".$Spotlightlist->getId(); ?>">Feed URL</a></label>
				</div>
				
				<div class="row">
					<label for=""><a href="<?php echo SERVER_ADDRESS; ?>">Back to Lists</a></label>
				</div>
				
				</form>
			</div>
			<a href="previewSpotlightList.php?listID=<?php echo $Spotlightlist->getId(); ?>">Preview List</a><br />
			<a href="previewSpotlightList.php?listID=<?php echo $Spotlightlist->getId(); ?>&ss=1">Preview List - with Sunrise/Sunset</a>
			
			<?php
				
			?>
			
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
			<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
			<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
			
			<script>
				$(function(){
					$( "#currentList ul, #availableSpifs ul, #trash ul" ).sortable({
					  connectWith: ".spif-list",
					  placeholder: "ui-state-highlight"
					}).disableSelection();	
					
					$('#saveSpifList').click(function(event){
						event.preventDefault();
						var t = $('#currentList li input[name="spotlightID"]').serializeArray();
						$.post("../saveList.php", {listID: "<?php echo $listID; ?>", items: t, count: t.length }).done( function(data){
							//console.log( data );
							if( $.trim( data ) == "Save OK!"){
								window.alert("Save OK");
								
								$('#trash li').remove();
							}else{
								window.alert("Save Failed");
							}
						});
						
					});
					
					$('#currentList ul li').each(function(){
						var parentId = $(this).find('input[name="spotlightID"]').attr('value');
						$('#availableSpifs ul li').not('.in-list').each(function(){
							if( parentId == $(this).find('input[name="spotlightID"]').attr('value') ){
								$(this).addClass("in-list");
							}
						});
					});
					
					
					
					//mark items in current list 
				});
			</script>
			
			<?php
				css();
			?>
			
			<hr />
			
			<button id="saveSpifList">Save/Update List</button>
			
			<div class="clear"></div>
		
			
			<div id="listManager">
				<div id="currentList">
					<h2>Current List</h2>
					<ul class="spif-list currentList">
					<?php
							$query = $conn->prepare("SELECT * FROM `spotlightlistitem` WHERE `listId` = :listID ORDER BY `itemOrder` ASC");
							$query->bindParam(':listID', $listID);
							if( $query->execute() ){
								while( $spotlight = $query->fetchObject("spotlightlistitem") ){
									$spifID = $spotlight->getSpifId();
									$spotlight = new Spotlight($conn);
									$spotlight = $spotlight->load( $spifID );
								
									echo '<li>'.$spotlight->getTitle().'<br /><img src="'.$spotlight->getImagePath().'" /><input type="hidden" name="spotlightID" value="'.$spotlight->getId().'" />'.renderToolSet($spotlight).'</li>';
								}
							}
						?>
					</ul>
				</div>
				<div id="availableSpifs">
					<h2>Available Spotlights</h2>
					<ul class="spif-list availableList">
						<?php
							$query = $conn->prepare("SELECT * FROM `spotlight` ORDER BY `lastModified` DESC");
							if( $query->execute() ){
								while( $spotlight = $query->fetchObject("spotlight") ){
									echo '<li>'.$spotlight->getTitle().'<br /><img src="'.$spotlight->getImagePath().'" /><input type="hidden" name="spotlightID" value="'.$spotlight->getId().'" />'.renderToolSet($spotlight).'</li>';
								}
							}
						?>
					</ul>
				</div>
				<div id="trash" style="margin-top: 20px;">
					<h2>Trash</h2>
					<ul class="spif-list trashList" style="min-height: 100px; ">
					</ul>	
					</div>
				</div>
			</div>
			
			
			
		<?php
	}else{
		echo "Could not find List";
	}
?>
<div class="clear"></div>

