<?php
define('FS_ROOT_OVERRIDE', realpath(dirname(__FILE__)));
require_once (FS_ROOT_OVERRIDE . "/../../www/config.php");
require_once (FS_ROOT_OVERRIDE . "/../../www/lib/framework/db.php");

	$fileout = "/var/www/newznab/misc/testing/bookout.txt";
		
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
		"&"
	);		
		
	$excludedPublishers = array(
	);
		
	function getBooks()
	{			
		$db = new DB();
		return $db->query("SELECT * from releases r join bookinfo bi on r.bookinfoID = bi.ID ");		
	}
	
	$books =  getBooks();
	
	$output = "";
	foreach($books as $book) 
	{		
		$i = 0;
		if (!in_array($book['publisher'],$excludedPublishers))
		{	
			$titlewords = explode(" ", $book['name']);
			$publisherwords = explode(" ", $book['publisher']);
			
			foreach($titlewords as $word) 
			{
				if (!in_array(strtolower($word),$excludedWords))
				{
					if(in_array($word,$publisherwords))
					{
						$i = $i + 1;
						if ($i == 2) //two matching words. Change to experiment
						{						
							//add to the output file. When tweaked enough, releases and bookinfo shall be nuked at this point						
							$output.= $book['name']." - ".$book['publisher']."\n";
							break;
						}
						
					}
				}
			}
		}
	}
			
	file_put_contents($fileout,$output);		
	
?>