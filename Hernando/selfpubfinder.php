<?php
define('FS_ROOT_OVERRIDE', realpath(dirname(__FILE__)));
require_once (FS_ROOT_OVERRIDE . "/../../www/config.php");
require_once (FS_ROOT_OVERRIDE . "/../../www/lib/framework/db.php");
require_once (FS_ROOT_OVERRIDE . "/../../www/lib/releases.php");

// ADDED BY Hernando
$countself = 0;
///////////////////

$echo = true;
$NNBookCoverPath = "/var/www/newznab/www/covers/book/";
	
$excludedWords = array(
	"the",
	"this",
	"that",
	"a",
	"i",
	"of",
	"at",
	"for",
	"oh",
	"my",
	"and",
	"cinematographer",
	"house",
	"chicken",
	"soup",
	"soul",
	"star",
	"barnes",
	"noble",
	"classics",
	"pc",
	"world",
	"national",
	"geographic",
	"scientific",
	"american",
	"aa",
	"services",
	"teach",
	"yourself",
	"dummies",
	"dk",
	"travel",
	"guinness",
	"records",
	"general",
	"radio",
	"company",
	"mcgraw-hill",
	"professional",
	"artech",
	"current",
	"clinical",
	"strategies",
	"society",
	"industrial",
	"applied",
	"mathematics",
	"arms",
	"armor",
	"quilter",
	"infantry",
	"journal",
	"marco",
	"polo",
	"cold",
	"spring",
	"harbor",
	"laboratory",
	"focal",
	"cisco",
	"how",
	"good",
	"design",
	"originals",
	"breckling",
	"microsoft",
	"peachpit",
	"leisure",
	"arts",
	"course",
	"technology",
	"mysql",
	"institution",
	"structural",
	"engineers",
	"sound",
	"vision",
	"webster's",
	"new",
	"tab",
	"electronics",
	"mcgraw-hill",
	"university",
	"institute",
	"strategic",
	"studies",
	"international",
	"int'l",
	"science",
	"mind",
	"marine",
	"corporation",
	"cambridge",
	"new",
	"word",
	"city",
	"inc",
	"us",
	"army",
	"corps",
	"u.s.",
	"war",
	"department",
	"new",
	"riders",
	"starch",
	"piatkus",
	"prentice",
	"hall",
	"price",
	"pottenger",
	"nutrition",
	"pressure",
	"vessel",
	"handbook",
	"shack",
	"tourism",
	"organisation",
	"serbia",
	"ireland",
	"labouff",
	"creative",
	"print",
	"hard",
	"case",
	"crime",
	"engineering",
	"paraglyph",
	"profile",
	"ltd",
	"books/star",
	"sas",
	"&",
);		
	
$excludedPublishers = array(
);
	
function getBooks()
{			
	$db = new DB();
	return $db->query("SELECT r.id, r.name, bi.id as bookid, bi.publisher from releases r join bookinfo bi on r.bookinfoID = bi.ID ");		
}

function deleteBook($book)
{
	$db = new DB();
	global $NNBookCoverPath;
	if ($book['cover'] = 1)
	{
		//delete the cover
		if (file_exists ($NNBookCoverPath.$book['bookid'].".jpg"))
		{ 
		unlink($NNBookCoverPath.$book['bookid'].".jpg");
		}
	}
	
	$db->query(sprintf("DELETE FROM bookinfo WHERE id = %d ",$book['bookid']));
	
}

$releases = new Releases();
$books =  getBooks();


foreach($books as $book) 
{		
	$i = 0;
	if (!in_array(strtolower($book['publisher']),$excludedPublishers))
	{	
		$titlewords = explode(" ", strtolower($book['name']));
		$publisherwords = explode(" ", strtolower($book['publisher']));
		
		foreach($titlewords as $word) 
		{
			if (!in_array($word,$excludedWords))
			{
				if(in_array($word,$publisherwords))
				{
					$i = $i + 1;
					if ($i == 2) //two matching words. Change to experiment
					{						
						if ($echo == false)
						{				
							deleteBook($book);
							$releases->delete($book['id']);
						}
						
						// ADDED BY Hernando
						echo "\033[1;0;33m "  .$book['name']. " -\033[1;1;35m " .$book['publisher']. "\n\033[1;0;36m";
						$countself = $countself + 1;
						/////////////////////////////////////
						
						break;
					}
					
				}
			}
		}
	}
}
			
// ADDED BY Hernando
echo "\033[1;1;32m\n\nDeleted $countself releases \n\n\n\033[1;0;36m";
///////////////////////////

?>