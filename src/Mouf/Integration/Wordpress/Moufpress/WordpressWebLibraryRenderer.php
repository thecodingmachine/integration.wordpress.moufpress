<?php
namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Html\Utils\WebLibraryManager\WebLibraryInterface;

use Mouf\Html\Utils\WebLibraryManager\WebLibraryRendererInterface;

/**
 * The WordpressWebLibraryRenderer class is the Wordpress way of adding JS ans CSS files.
 *  
 * @author David Négrier
 */
class WordpressWebLibraryRenderer implements WebLibraryRendererInterface {
	
	private static $count = 0;
	
	/**
	 * Renders the CSS part of a web library.
	 *
	 * @param WebLibrary $webLibrary
	 */
	public function toCssHtml(WebLibraryInterface $webLibrary) {
		$rootUrl = get_bloginfo('url');
		$files = $webLibrary->getCssFiles();
		if ($files) {
			foreach ($files as $file) {
				if(strpos($file, 'http://') === false && strpos($file, 'https://') === false && strpos($file, '/') !== 0) {
					wp_enqueue_script('moufpress_style_'.self::$count, $rootUrl.'/'.$file);
				} else {
					wp_enqueue_script('moufpress_style_'.self::$count, $file);
				}
				self::$count++;
			}
		}
	}
	
	/**
	 * Renders the JS part of a web library.
	 *
	 * @param WebLibrary $webLibrary
	 */
	public function toJsHtml(WebLibraryInterface $webLibrary) {
		$rootUrl = get_bloginfo('url');
		$files = $webLibrary->getJsFiles();
		if ($files) {
			foreach ($files as $file) {
				if(strpos($file, 'http://') === false && strpos($file, 'https://') === false && strpos($file, '/') !== 0) {
					wp_enqueue_script('moufpress_style_'.self::$count, $rootUrl.'/'.$file);
				} else {
					wp_enqueue_script('moufpress_style_'.self::$count, $file);
				}
			}
		}
		
	}
	
	/**
	 * Renders any additional HTML that should be outputed below the JS and CSS part.
	 *
	 * @param WebLibrary $webLibrary
	 */
	public function toAdditionalHtml(WebLibraryInterface $webLibrary) {
		return "";
	}
}