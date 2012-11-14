<?php
/**
 * Jesse Jordan 2012
 * interface.parseInterface.php
 * 
 * A definition of the parsing interface
 */

interface parseInterface {
	/**
	 * The name of the file within the data/ directory to parse
	 * @param string $filename
	 */
	public function __construct($filename);
	
	/**
	 * Did the parser succeed?
	 * @return bool
	 */
	public function isParserReady();
	
	/**
	 * Get the SEOmozume sections
	 */
	public function getSections();
	
	/**
	 * Get all subsections within $section
	 * @param string $section
	 */
	public function getSubSections($section);
	
	/**
	 * Get all bullets within the $section and $subsection
	 * @param string $section
	 * @param string $subsection
	 */
	public function getBullets($section, $subsection);
}