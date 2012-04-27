<?php
/*!
 * @desc Controller to link code to views.
 * @author Shawn Melton <shawn.a.melton@gmail.com>
 * @version $id: $
 */
class Controller {
	protected $_content;
	protected $_domain;
	protected $_layout;
	protected $_siteRoot;
	protected $_stylePath;
	protected $_viewPath;
	
	public function __construct() {
		$this->_domain = false;
		$this->_layout = 'layout.phtml';
		$this->_siteRoot = dirname(dirname(__FILE__));
		
		$this->_viewPath = $this->_siteRoot .'/';
		$this->_stylePath = '/';
	}
	
	
	/*!
	 * @desc Render the page thats being called.
	 * We have our default pages below: Contact Us, Find Us and Home.
	 * If we want to add another page, lets search for the template.  If it exists, render page.  Otherwise 404.
	 */
	public function __call($name, $args) {
		// Site specific views will be placed in the theme folder.
		if( !file_exists($this->_siteRoot .'/httpdocs/themes/'. $this->_getDomain() .'/views/'. $name .'.phtml') ) {
			header('HTTP/1.0 404 Not Found');
			$this->_template = '404.phtml';
			return;
		}
		
		$this->_template = $name .'.phtml';
	}
	
	
	/*!
	 * @desc get the current domain that is being requested.
	 * Allow for domain overrides here.
	 * @return <string>
	 */
	protected function _getDomain() {
		if( $this->_domain === false ) {
			$this->_domain = $this->_translate(str_replace('/', '', $_SERVER['HTTP_HOST']));
			if( IN_DEV ) {
				$this->_domain = str_replace('dev.', '', $this->_domain);
			}
			
			if( !file_exists($this->_siteRoot .'/httpdocs/themes/'. $this->_domain) ) {
				$this->_domain = 'example.com';
			}
		}
		
		return $this->_domain;
	}
	
	
	/*!
	 * @desc Get the page title
	 * @return <string> The page title.
	 */
	protected function _getTitle() {
		$title = ucwords(rtrim(trim(preg_replace('/\-|\//', ' ', str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']))), '?'));
		if( $this->_template == '404.phtml' ) {
			$title = 'Page Not Found';
		} else if( $title == '' ) {
			$title = 'Home';
		}
	
		return $title;
	}
	
	
	/*!
	 * @desc Translate a domain to the proper alias - if applicable.
	 * @return appropriate domain for site.
	 */
	protected function _translate($domain) {
		$aliases = array(
			'neptunefestivalmobile.webteks.com' => 'm.neptunefestival.com',
			'budsusamobile.webteks.com' => 'm.budsusa.com'
		);
		
		return isset($aliases[$domain]) ? $aliases[$domain] : $domain;
	}
	
	
	/*!
	 * @desc The Contact form page of the site - should post to the site form. (CURL or ajax).
	 */
	public function contactus() {
		$this->_template = 'contactus.phtml';
	}
	
	
	/*!
	 * @desc Display the page information
	 */
	public function display() {
		// determine the style path.  if it DNE, then render from httpdocs folder.
		$stylePath = '/themes/'. $this->_getDomain() .'/';
		
		$site = new Site();
		$site->load($this->_siteRoot .'/httpdocs/themes/'. $this->_getDomain() .'/');
	
		// render content view.
		$content = new View();
		$content->file = $this->_template;
		$content->site = $site;
		$content->stylePath = $stylePath;
		$html = $content->render($this->_getDomain());
		
		// render navigation view
		$nav = new View();
		$nav->file = 'navigation.phtml';
		$nav->site = $site;
		$html .= $nav->render($this->_getDomain());
		
		// render content layout.
		$container = new View();
		$container->file = $this->_layout;
		$container->stylePath = $stylePath;
		$container->content = $html;
		$container->title = $this->_getTitle();
		$container->site = $site;
		echo $container->render($this->_getDomain());
	}
	
	
	/*!
	 * @desc The Location page of the site - display a google map.
	 */
	public function findus() {
		$this->_template = 'findus.phtml';
	}


	/*!
	 * @desc The home page of the site.
	 */
	public function home() {
		$this->_template = 'home.phtml';
	}
	
	
	/*!
	 * @desc Mail a form submission somewhere.
	 */
	public function mail() {
		if( isset($_POST['fns']) && $_POST['fns'] ) {
			FormMail::send($this->_getDomain());
			exit;
		}
		
		header('HTTP/1.0 404 Not Found');
		$this->_template = '404.phtml';
		return;
	}
}