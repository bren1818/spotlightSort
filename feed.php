<?php
	include "includes.php";
	$listID = $_REQUEST['feed'];
	$conn = getConnection();
	$Spotlightlist = new Spotlightlist($conn);
	
	$Spotlightlist = $Spotlightlist->load($listID);
	
	$query = $conn->prepare("SELECT COUNT(*) as `cnt` FROM `spotlightlistitem` WHERE `listId` = :listID");
			$query->bindParam(':listID', $listID);
			$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);
	
	
	$count = $result['cnt'];
	
	
	if( $Spotlightlist->getId() > 0 ){
		header('Content-type: application/xml');
		echo '<xml>';
		echo '<rss encoding="UTF-8" version="2.0">';
		echo '<generator>Bren\'s Awesome Scripts</generator>';
		echo '<count>'.$count.'</count>';
		echo '<items>';
		$query = $conn->prepare("SELECT * FROM `spotlightlistitem` WHERE `listId` = :listID ORDER BY `itemOrder` ASC");
		$query->bindParam(':listID', $listID);
		if( $query->execute() ){
			while( $spotlight = $query->fetchObject("spotlightlistitem") ){
				$spifID = $spotlight->getSpifId();
				$spotlight = new Spotlight($conn);
				$spotlight = $spotlight->load( $spifID );
				echo '<item>';
					echo '<type>'.$spotlight->getType().'</type>';
					echo '<name>'.$spotlight->getName().'</name>';
					echo '<title>'.$spotlight->getTitle().'</title>';
					echo '<link>'.$spotlight->getLink().'</link>';
					echo '<is-published>'.$spotlight->getPublished().'</is-published>';
					echo '<image-path>'.$spotlight->getImagePath().'</image-path>';
				echo '</item>';
			}
		}
		echo '</items>';
		echo '</rss>';
		echo '</xml>';
	}
?>