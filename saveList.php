<?php
	include "includes.php";
	
	//pa( $_POST );
	
	if( isset($_POST['listID']) &&  isset( $_POST['items']) && isset( $_POST['count']) ){
	
		$listID = $_POST['listID'];
		$items = $_POST['items'];
		$count = $_POST['count'];
		
		$conn = getConnection();
		
		$query = $conn->prepare("DELETE FROM `spotlightlistitem` WHERE `listId` = :id");
		$query->bindParam('id', $listID);
		$query->execute();
		
		$order = 0;
		$errors = 0;
		foreach( $items as $item ){
			//echo $item["value"].'<br />';
			$Spotlightlistitem = new Spotlightlistitem($conn);
			$Spotlightlistitem->setListId($listID);
			$Spotlightlistitem->setSpifId($item["value"]);
			$Spotlightlistitem->setItemOrder($order);
			if( $Spotlightlistitem->save() > 0 ){
			
			}else{
				$errors++;
			}
			$order++;
		}
	
		if( $errors == 0 ){
			echo "Save OK!";
		}else{
			echo $errors." Errors!";
		}
	
	}else{
		echo "Invalid Input";
	}
?>