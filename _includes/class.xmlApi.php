<?php
/**
 * Jesse Jordan 2012
 * class.xmlApi.php
 * 
 * output API data in XML
 */

class xmlApi extends apiBase implements apiInterface {
	public function __construct() { 
		//void 
	}
	
	/**
	 * @see apiInterface::printOutput()
	 */
	public function printOutput($section = null, $subsection = null, $bullet_id = null) {
		//prepare XML object
		$xml_obj = new SimpleXMLElement('<output/>');
		//if they want all sections
		if($section === null) {
			//loop through all sections and add them
			foreach ($this->sectionArray as $section_to_append) {
				$this->appendSectionXml($xml_obj, $section_to_append);
			}
		} elseif(in_array($section, $this->sectionArray)) {
			$this->appendSectionXml($xml_obj, $section, $subsection, $bullet_id);
		} else {
			$this->errorArray[] = 'Cannot find section '.$section;
		}
		
		//append any applicable errors
		$errors_xml = $xml_obj->addChild('errors');
		foreach ($this->errorArray as $error) {
			$errors_xml->addChild('error',$error);
		}

		print $xml_obj->asXML();
	}
	
	/**
	 * 
	 * @param SimpleXMLElement $xml_obj
	 * @param string $section
	 * @param string $subsection
	 * @param int $bullet_id
	 */
	private function appendSectionXml(SimpleXMLElement & $xml_obj, $section, $subsection = null, $bullet_id = null) {
		$new_section = $xml_obj->addChild($section);
		
		//if subsection is null, append all subsections, otherwise just the specified one
		if($subsection === null) {
			foreach ($this->subSectionArray as $subsection_to_append => $valid_section) {
				//if this is valid for this subsection, add it
				if($section == $valid_section) {
					$this->appendSubSectionXml($new_section, $subsection_to_append);
				}
			}
		} elseif(isset($this->subSectionArray[$subsection]) 
			  && $this->subSectionArray[$subsection] == $section) {
			$this->appendSubSectionXml($new_section, $subsection, $bullet_id);
		} else {
			$this->errorArray[] = 'Subsection '.$subsection.' not in '.$section;
		}
	}

	/**
	 * 
	 * @param SimpleXMLElement $xml_obj
	 * @param string $subsection
	 * @param int $bullet_id
	 */
	private function appendSubSectionXml(SimpleXMLElement & $xml_obj, $subsection, $bullet_id = null) {
		$new_subsection = $xml_obj->addChild($subsection);
		//if bullet_id is null, append all subsections, otherwise just the specified one
		if($bullet_id === null) {
			foreach ($this->bulletSubSectionLinkArray as $bullet_to_append => $valid_subsection) {
				//if this bullet is valid for this subsection, add it 
				if($subsection == $valid_subsection) {
					$this->appendBulletXml($new_subsection, $bullet_to_append);
				}
			}
		} elseif($this->bulletSubSectionLinkArray[$bullet_id] == $subsection) {
			$this->appendBulletXml($new_subsection, $bullet_id);
		} else {
			$this->errorArray[] = 'Bullet ID '.$bullet_id.' not in '.$subsection;
		}
	}
		
	/**
	 * 
	 * @param SimpleXMLElement $xml_obj
	 * @param int $bullet_id
	 */
	private function appendBulletXml(SimpleXMLElement & $xml_obj, $bullet_id) {
		$new_bullet = $xml_obj->addChild('bullet', $this->bulletArray[$bullet_id]);
		$new_bullet->addAttribute('id', $bullet_id);
	}
}