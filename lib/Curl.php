<?php
/*!
 * @desc Curl class
 * @author Shawn Melton <shawn.a.melton@gmail.com>
 * @version $id: $
 */
class Curl {
	/*!
	 * @desc Curl a specific url and return its content.
	 * @return <string>
	 */
	static function getContent($url) {
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_FOLLOWLOCATION => true
		));
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

	
	/*!
	 * @desc Fetch the mobile content for a cms site page.
	 * Content should be returned in a JSON encoded object with the index of the html called "html".
	 * @return <string> HTML content for mobile page.
	 */
	static function getSitePageContent($url) {
		$content = json_decode(self::getContent($url));
		if( is_object($content) && isset($content->html) ) {
			return $content->html;
		}
		
		return '';
	}

	
	/*!
	 * @desc Fetch the event information from an XML feed.  Document should be in this format:
	 * <events>
	 *	<event>
	 *		<name>...</name>
	 *		other attributes enclosed in tags.
	 *	</event>
	 * </events>
	 */
	static function getSiteEvents($url) {
		$events = array();
		$index = 0;
		
		$doc = new DOMDocument();
		if( $doc->loadXML(self::getContent($url)) ) {
			foreach( $doc->getElementsByTagName('event') as  $event ) {
				if( $event->firstChild ) {
					$child = $event->firstChild;
					while( $child ) {
						$events[$index][$child->localName] = $child->textContent;
						$child = $child->nextSibling;
					}
					
					$index++;
				}
			}
		}
		
		return $events;
	}
}