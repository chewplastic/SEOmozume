<?php
/**
 * Jesse Jordan 2012
 * class.jsonApi.php
 *
 * output API data in JSON
 */

class jsonApi extends apiBase implements apiInterface {
	public function __construct() { 
		//void 
	}
	
	/**
	 * @see apiInterface::printOutput()
	 */
	public function printOutput($section = null, $subsection = null, $bullet_id = null) {
		//prepare array
		$json_array = array(
			'sections'	=> array(),
		);
		//if they want all sections
		if($section === null) {
			//loop through all sections and add them
			foreach ($this->sectionArray as $section_to_append) {
				$this->appendSectionJson($json_array, $section_to_append);
			}
		} elseif(in_array($section, $this->sectionArray)) {
			$this->appendSectionJson($json_array, $section, $subsection, $bullet_id);
		} else {
			$this->errorArray[] = 'Cannot find section '.$section;
		}
		
		//append any applicable errors
		$json_array['errors'] = array();
		foreach ($this->errorArray as $error) {
			$json_array['errors'][] = $error;
		}

		print json_encode($json_array);
	}
	
	/**
	 * 
	 * @param array $json_array
	 * @param string $section
	 * @param string $subsection
	 * @param int $bullet_id
	 */
	private function appendSectionJson(array & $json_array, $section, $subsection = null, $bullet_id = null) {
		$json_array['sections'][$section] = array();
		
		//if subsection is null, append all subsections, otherwise just the specified one
		if($subsection === null) {
			foreach ($this->subSectionArray as $subsection_to_append => $valid_section) {
				//if this is valid for this subsection, add it
				if($section == $valid_section) {
					$this->appendSubSectionJson($json_array['sections'][$section], $subsection_to_append);
				}
			}
		} elseif(isset($this->subSectionArray[$subsection]) 
			  && $this->subSectionArray[$subsection] == $section) {
			$this->appendSubSectionJson($json_array['sections'][$section], $subsection, $bullet_id);
		} else {
			$this->errorArray[] = 'Subsection '.$subsection.' not in '.$section;
		}
	}

	/**
	 * 
	 * @param array $json_array
	 * @param string $subsection
	 * @param int $bullet_id
	 */
	private function appendSubSectionJson(array & $json_array, $subsection, $bullet_id = null) {
		$json_array[$subsection] = array();
		//if bullet_id is null, append all subsections, otherwise just the specified one
		if($bullet_id === null) {
			foreach ($this->bulletSubSectionLinkArray as $bullet_to_append => $valid_subsection) {
				//if this bullet is valid for this subsection, add it 
				if($subsection == $valid_subsection) {
					$this->appendBulletJson($json_array[$subsection], $bullet_to_append);
				}
			}
		} elseif($this->bulletSubSectionLinkArray[$bullet_id] == $subsection) {
			$this->appendBulletJson($json_array[$subsection], $bullet_id);
		} else {
			$this->errorArray[] = 'Bullet ID '.$bullet_id.' not in '.$subsection;
		}
	}
		
	/**
	 * 
	 * @param array $json_array
	 * @param int $bullet_id
	 */
	private function appendBulletJson(array & $json_array, $bullet_id) {
		$json_array[$bullet_id] = $this->bulletArray[$bullet_id]; 
	}
}
