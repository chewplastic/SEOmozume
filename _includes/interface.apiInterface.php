<?php
/**
 * Jesse Jordan 2012
 * interface.apiInterface.php
 * 
 * defines the interface for the API
 */

interface apiInterface {
	/**
	 * 
	 */
	public function __construct();
	
	/**
	 * Adds a new section
	 * @param string $section
	 */
	public function addSection($section);
	
	/**
	 * Adds a subsection
	 * @param string $section
	 * @param string $subsection
	 */
	public function addSubSection($section, $subsection);
	
	/**
	 * Adds bullet data 
	 * @param string $section
	 * @param string $subsection
	 * @param int $bullet_id
	 * @param string $bullet_data
	 */
	public function addBullet($section, $subsection, $bullet_id, $bullet_data);
	
	/**
	 * Creates the API output 
	 * @param string $section
	 * @param string $subsection
	 * @param int $bullet_id
	 * @param string $bullet_data
	 */
	public function printOutput($section = null, $subsection = null, $bullet_id = null);
}