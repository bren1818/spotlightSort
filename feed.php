<?php
	include "includes.php";
	$listID = $_REQUEST['feed'];
	$conn = getConnection();
	$Spotlightlist = new Spotlightlist($conn);
	
	$Spotlightlist = $Spotlightlist->load($listID);
	
	/*
	$query = $conn->prepare("SELECT COUNT(*) as `cnt` FROM `spotlightlistitem` WHERE `listId` = :listID");
			$query->bindParam(':listID', $listID);
			$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);
	
	
	$count = $result['cnt'];
	*/
	
	function makeMeSafe( $str, $key ){
		return '<'.$key.'>'.htmlspecialchars($str).'</'.$key.'>';
	}
	
	function renderXMLSpotlightItem( $spotlight ){
		$xml = '<item>'.
			//makeMeSafe($spotlight->getType(), 'type').
			makeMeSafe($spotlight->getName(), 'name').
			makeMeSafe($spotlight->getTitle(), 'title').
			makeMeSafe($spotlight->getLink(), 'link').
			makeMeSafe($spotlight->getPublished(), 'is-published').
			makeMeSafe($spotlight->getImagePath(),'image-path').
			makeMeSafe($spotlight->getNewWindow(),'new-window').
		    '</item>';
		return $xml;
	}
	
	$count = 0;
	$time = time(); //numeric value
	
	//use Cacher Class to cache this
	
	
	if( $Spotlightlist->getId() > 0 ){
		header('Content-type: application/xml');
		echo '<xml>';
		echo '<rss encoding="UTF-8" version="2.0">';
		echo makeMeSafe($Spotlightlist->getListName(), 'feedName'); 
		echo makeMeSafe($Spotlightlist->getDescription(), 'feedDescription'); 
		echo '<generated>'.timeStampToDate( $time ).'</generated>';
		echo '<items>';
		$query = $conn->prepare("SELECT * FROM `spotlightlistitem` WHERE `listId` = :listID ORDER BY `itemOrder` ASC");
		$query->bindParam(':listID', $listID);
		
		if( $query->execute() ){
			while( $spotlight = $query->fetchObject("spotlightlistitem") ){
				$spifID = $spotlight->getSpifId();
				$spotlight = new Spotlight($conn);
				$spotlight = $spotlight->load( $spifID );
				//Check if the Spotlight has been risen or set
				if( $spotlight->getSunrise() != "" && $spotlight->getSunset() != "" ){	
					if( $spotlight->getSunrise() <= $time && $spotlight->getSunset() >= $time  ){
						echo renderXMLSpotlightItem($spotlight);
						$count ++;
					}
				}else{
					echo renderXMLSpotlightItem($spotlight);
					$count ++;
				}
			}
		}
		echo '</items>';
		echo '<count>'.$count.'</count>';
		echo '</rss>';
		echo '</xml>';
	}
?>