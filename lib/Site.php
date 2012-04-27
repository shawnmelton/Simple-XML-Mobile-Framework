<?php
/*!
 * @desc Handle information pertaining to the specific site that we are serving.
 * @author Shawn Melton <shawn.a.melton@gmail.com>
 * @version $id: $
 */
class Site extends BaseObject {
	protected $_links;
	protected $_forms;
	
	public function __construct() {
		$this->_forms = $this->_links = array();
	}
	
	
	/*!
	 * @desc Load the form information.
	 */
	protected function _loadForm($form) {
		$fields = array();
		foreach( $form->getElementsByTagName('field') as $field ) {
			$html = '
			<li>
				<label for="'. $field->getAttribute('name') .'">'. $field->getAttribute('label') .' '. ($field->getAttribute('required') == 'true' ? '<span class="req">*</span>' : '') .'</label>
			';
			
			switch( $field->getAttribute('type') ) {
				case 'textarea': $html .= '<textarea name="'. $field->getAttribute('name') .'" id="'. $field->getAttribute('name') .'"'. ($field->getAttribute('required') == 'true' ? ' required="required"' : '') .'></textarea>'; break;
				case 'select': 
					$html .= '<select name="'. $field->getAttribute('name') .'" id="'. $field->getAttribute('name') .'"'. ($field->getAttribute('required') == 'true' ? ' required="required"' : '') .'>';
					$html .= '<option value="">-</option>';
					foreach( $field->getElementsByTagName('option') as $option ) {
						$html .= '<option value="'. $option->getAttribute('value') .'">'. $option->getAttribute('value') .'</option>';
					}
					
					$html .= '</select>';
					break;
					
				case 'radio': $html .= 'TODO'; break;
				default: $html .= '<input type="'. $field->getAttribute('type') .'" name="'. $field->getAttribute('name') .'" id="'. $field->getAttribute('name') .'"'. ($field->getAttribute('required') == 'true' ? ' required="required"' : '') .' value="">'; break;
			}
			
			$fields[] = $html .'</li>';
		}
		
		$this->_forms[$form->getAttribute('name')] = array(
			'name' => $form->getAttribute('name'),
			'recipients' => $form->hasAttribute('recipients') ? $form->getAttribute('recipients') : 'bugs@webteks.com',
			'method' => $form->getAttribute('method'),
			'action' => $form->getAttribute('action'),
			'fields' => $fields
		);
	}
	

	/*!
	 * @desc Load up the site navigation links.
	 */
	protected function _loadNavLinks($nav) {
		foreach( $nav->getElementsByTagName('link') as $link ) {
			$this->_links[$link->getAttribute('title')] = $link->textContent;
		}
	}
	
	
	/*!
	 * @desc Get the array of the site links.
	 * @return <array>
	 */
	public function getLinks() {
		return $this->_links;
	}
	
	
	/*!
	 * @desc Get the requested form information
	 * @param $form - The form that we want fields from.
	 * @param $token - Specific information that we are requesting.
	 * @return <mixed>
	 */
	public function getFormInfo($form, $token) {
		if( isset($this->_forms[$form]) && isset($this->_forms[$form][$token]) ) {
			return $this->_forms[$form][$token];
		}
	
		return false;
	}
	

	/*!
	 * @desc Load the XML information into this object for use.
	 */
	public function load( $path ) {
		$xmlFile = $path . 'site.xml';
		if( file_exists($xmlFile) ) {
			$doc = new DomDocument();
			if( $doc->load($xmlFile) ) {
				foreach( $doc->getElementsByTagName('site') as  $site ) {
					if( $site->firstChild ) {
						$child = $site->firstChild;
						while( $child ) {
							if( $child->localName == 'nav' ) {
								$this->_loadNavLinks($child);
							} else if( $child->localName == 'form' ) {
								$this->_loadForm($child);
							} else {
								$this->__set($child->localName, $child->textContent);
							}
						
							$child = $child->nextSibling;
						}
					}
				}
			}
		}
	}

}