<?php
/*!
 * @desc Render a view template.
 * @author Shawn Melton <shawn.a.melton@gmail.com>
 * @verison $id: $
 */
class View extends BaseObject {
	/*!
	 * @desc Make sure that all the proper settings are in place and the template file exists.
	 */
	protected function _validate() {
		if( $this->file === false ) {
			throw new Exception('You have failed to set the template file that this view is supposed to render.');
		}
		
		if( !file_exists($this->path . $this->file) ) {
			throw new Exception('The following template file does not exist: '. $this->path . $this->file);
		}
	}

	public function render($domain) {
		$this->path = dirname(dirname(__FILE__)) .'/views/';
		
		// Allow theme overwriting of views.
		if( file_exists(dirname($this->path) .'/httpdocs/themes/'. $domain .'/views/'. $this->file) ) {
			$this->path = dirname($this->path) .'/httpdocs/themes/'. $domain .'/views/';
		}
		
		$this->_validate();
	
		ob_start();
		require_once $this->path . $this->file;
		return ob_get_clean();	
	}
}