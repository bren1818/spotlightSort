<?php
	include "includes.php";
	
	$conn = getConnection();
	
	//$query = $conn->prepare("TRUNCATE `spotlight`");
	//$query->execute();
	
	$sl = simplexml_load_file('Spotlight_Data.xml', null,LIBXML_NOCDATA);

	$title = $sl->rss->title;
	$itemCount = $sl->rss->count;
	$items = 	$sl->rss->items;
	
	
	echo "<h2>Checking for new Spotlights from Published Spotlight File</h2>";
	
	$items = json_decode(json_encode($items), true);
	
	$alreadyInDB = 0;
	$notInDB = 0;
	
	echo '<ul>';
	foreach( $items['item'] as $item ){
		$type = $item['type'];
		$name = $item['name'];
		$title =  $item['title']; //issue with character encoding here
		$published = $item['is-published'];
		$link =  str_replace('site://','http://',$item['link']);
		$lastModified =  $item['last-modified'];
		$tags = implode(", ", explode(",",rtrim($item['tags'], ',')));
		
		$imagePath = str_replace('site://','//',$item['image-path']);
		
		if( startsWith("/",$imagePath) ){
			$imagePath = "http://wlu.ca".$imagePath;
		}

		if( is_array($published) ){
			$published = "Unknown";
		}
		
		$id = md5($link);
		$lookup = new Spotlight( $conn );
		$lookup = $lookup->getListByCmsid($id);
		
		$query = $conn->prepare("SELECT COUNT(*) as `cnt` FROM `spotlight` WHERE `cmsid` = :cmsid");
		$query->bindParam(':cmsid', $id);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		$count = $result['cnt'];
		
		
		if( $count == 1 ){
			//already in our DB
			$alreadyInDB++;
		}else{
			$notInDB++;
			echo '<li>Added new Spotlight: '.$title.'</li>';
			$spotlight = new Spotlight($conn);
				$spotlight->setCmsid( md5($link) );
				$spotlight->setType( $item['type'] );
				$spotlight->setName( $item['name'] );
				$spotlight->setTitle( $title );
				$spotlight->setPublished( $published );
				$spotlight->setImagePath( $imagePath );
				$spotlight->setLink( $link );
				$spotlight->setNewWindow( 0 );
				$spotlight->setLastModified( $lastModified  ); //date("Y-m-d H:m:s",)
				$spotlight->setDeleted( 0 );
				$spotlight->setTags( $tags );
			$spotlight->save();
		}

	}
	echo '</ul>';
	echo '<p>Added: '.$notInDB.' to Database, '.$alreadyInDB.' already in Database</p>';
	
	
	echo '<p><a href="views/newSpotlightList.php">Create Spotlight Feed</a></p>';
	echo '<p><a href="CreateSpotlight.php">Create Spotlight</a></p>';
	echo '<p><a href="viewSpotlights.php">Edit Spotlight(s)</a></p>';
	
	
	
	
	echo '<p>Manage Spotlight List:</p>';
	echo "<ul>";
		$query = $conn->prepare("SELECT * FROM `spotlightlist` ORDER BY `lastUpdated` DESC");
		if( $query->execute() ){
			while( $spotlight = $query->fetchObject("Spotlightlist") ){
				echo '<li><a href="views/modifyList.php?listID='.$spotlight->getId().'">'.$spotlight->getListName().'</a></li>';
			}
		}
	echo "</ul>";
	

?>