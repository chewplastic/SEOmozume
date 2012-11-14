<?php
/**
 * Jesse Jordan 2012
 * class.parserBase.php
 * 
 * Basic structures and methods for the parsers
 */

class parserBase {
	/**
	 * file handle, for the parsers that use it
	 * @var resource
	 */
	protected $fileHandle = null;
	
	/**
	 * Associative array of all parsed data
	 * @var array
	 */
	protected $parsedDataArray = array();
	
	protected function __construct() { }
	
	/**
	 * Simply check if anything was parsed
	 * @see parseInterface::isParserReady()
	 * @return boolean
	 */
	public function isParserReady() {
		return sizeof($this->parsedDataArray) > 0 
			? true : false;
	} 
	
	/**
	 * Fetch sections
	 * @return array
	 */
	final public function getSections() {
		return array_keys($this->parsedDataArray);
	}
	
	/**
	 * Fetch subsections for a section 
	 * @param string $section
	 */
	final public function getSubSections($section) {
		return array_keys($this->parsedDataArray[$section]);
	}
	
	/**
	 * Fetch bullets array for a section/subsection
	 * @param string $section
	 * @param string $subsection
	 */
	final public function getBullets($section, $subsection) {
		return $this->parsedDataArray[$section][$subsection];
	}
	
}