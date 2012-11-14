<?php
/**
 * Jesse Jordan 2012
 * class.apiBase.php
 * 
 * The basic methods that both API classes use for internally storing 
 * their resume data
 */

abstract class apiBase implements apiInterface {
	/**
	 * Array of sections
	 * @var array
	 */
	protected $sectionArray = array();
	/**
	 * Array of subsections as key, with sections as value 
	 * @var array
	 */
	protected $subSectionArray = array();
	
	/**
	 * Array of bullet data, keyed by bullet_id
	 * @var array
	 */
	protected $bulletArray = array();
	
	/**
	 * Array of bullet_ids as key and and their assigned subsections as the value
	 * @var array
	 */
	protected $bulletSubSectionLinkArray = array();
	
	/**
	 * Array of errors 
	 * @var array
	 */
	protected $errorArray = array();
	
	final public function addSection($section) {
		//if it's already present, show an error...
		if(in_array($section, $this->sectionArray)) {
			$this->errorArray[] = 'Duplicate Section Found';
			return;
		}
		
		//add the section
		$this->sectionArray[] = $section;
	}
	
	final public function addSubSection($section, $subsection) {
		//check if the subsection already exists
		if(isset($this->subSectionArray[$subsection])) {
			$this->errorArray[] = 'Duplicate SubSection Found';
			return;
		}
		
		//check if the section exists
		if(!in_array($section, $this->sectionArray)) {
			$this->errorArray[] = 'Section '.$section.' Not Found';
			return;
		}
		
		//ok, add the subsection
		$this->subSectionArray[$subsection] = $section;
	}
	
	final public function addBullet($section, $subsection, $bullet_id, $bullet_data) {
		//check if the section exists
		if(!in_array($section, $this->sectionArray)) {
			$this->errorArray[] = 'Section '.$section.' Not Found';
			return;
		}
		
		//check if the subsection exists
		if(!isset($this->subSectionArray[$subsection])) {
			$this->errorArray[] = 'SubSection '.$subsection.' Not Found';
			return;
		}
		
		//check if the bullet_id already exists
		if(isset($this->bulletArray[$bullet_id])) {
			$this->errorArray[] = 'Duplicate Bullet ID '.$bullet_id.' Found';
			return;
		}
		
		//okay, add the bullet data and associations
		$this->bulletArray[$bullet_id] = $bullet_data;
		$this->bulletSubSectionLinkArray[$bullet_id] = $subsection;		
	}
	
}