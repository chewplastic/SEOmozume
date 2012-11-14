<?php
/**
 * Jesse Jordan 2012
 * class.csvParser.php
 * 
 * A class that attempts to parse resume data from CSV input
 */

class csvParser extends parserBase implements parseInterface {
	/*
	 * CSV column positions to make code more readable
	 */
	const POS_SECTION 		= 0;
	const POS_SUBSECTION	= 1;
	const POS_BULLET		= 2;
	
	/**
	 * Reads in $filename from data/ and readies the object
	 * @param string $filename - path of filename relative to data/
	 */
	public function __construct($filename) {
		parent::__construct();
		//check for tampering
		if(strpos($filename, '..') !== false) {
			trigger_error('Filename tampering attempted!', E_USER_ERROR);
		}
		
		//open file
		$this->fileHandle = @fopen('data/'.$filename, 'r');
		
		//if we didn't get a file resource, bail
		if(!is_resource($this->fileHandle)) {
			return;
		}
		
		//temporary variables to track where parsing is at
		$current_section = null;
		$current_subsection = null;
		$current_bullet_id = 0; //arbitrarily incremented as we go
		//try to read in CSV
		while(($row = fgetcsv($this->fileHandle)) !== false) {
			//if this is a new section
			if(strlen(trim($row[self::POS_SECTION]))) {
				$current_section = trim($row[self::POS_SECTION]);
				$this->parsedDataArray[$current_section] = array();
			}
			
			//if this is a new subsection
			if(isset($row[self::POS_SUBSECTION]) && strlen(trim($row[self::POS_SUBSECTION]))) {
				if($current_section === null) {
					trigger_error('Subsection found before section', E_USER_ERROR);
				}
				
				$current_subsection = trim($row[self::POS_SUBSECTION]);
				$this->parsedDataArray[$current_section][$current_subsection] = array();
			}
			
			if(isset($row[self::POS_BULLET]) && strlen(trim($row[self::POS_BULLET]))) {
				if($current_section === null || $current_subsection === null) {
					trigger_error('Bullet found before section/subsection', E_USER_ERROR);
				}
				$this->parsedDataArray[$current_section][$current_subsection][$current_bullet_id] = trim($row[self::POS_BULLET]);

				//increment
				$current_bullet_id++;
			}					
		}
	}
	
	
}