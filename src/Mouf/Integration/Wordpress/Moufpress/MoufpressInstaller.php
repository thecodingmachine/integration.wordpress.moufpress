<?php
/*
 * Copyright (c) 2014 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Installer\PackageInstallerInterface;
use Mouf\MoufManager;
use Mouf\Actions\InstallUtils;

/**
 * The installer for Moufpress.
 */
class MoufpressInstaller implements PackageInstallerInterface {

	/**
	 * (non-PHPdoc)
	 * @see \Mouf\Installer\PackageInstallerInterface::install()
	 */
	public static function install(MoufManager $moufManager) {
		// Provide a defaultWebLibraryRenderer adapted to Drupal
		if ($moufManager->instanceExists("defaultWebLibraryRenderer")) {
			// Let's remove the default defaultWebLibraryRenderer :)
			$moufManager->removeComponent("defaultWebLibraryRenderer");
		}
		$wordpressWebLibraryRenderer = $moufManager->createInstance("Mouf\\Integration\\Wordpress\\Moufpress\\WordpressWebLibraryRenderer");
		$wordpressWebLibraryRenderer->setName("defaultWebLibraryRenderer");
		$jQueryLibrary = $moufManager->getInstanceDescriptor('jQueryLibrary');
		$wordpressWebLibraryRenderer->getConstructorArgumentProperty('replacedWebLibrary')->setValue(array('jquery' => $jQueryLibrary, ));
		
		// Let's create the instances.
		$wordpressTemplate = InstallUtils::getOrCreateInstance('wordpressTemplate', 'Mouf\\Integration\\Wordpress\\Moufpress\\WordpressTemplate', $moufManager);
		$content_block = InstallUtils::getOrCreateInstance('content.block', 'Mouf\\Html\\HtmlElement\\HtmlBlock', $moufManager);
		
		// Let's bind instances together.
		if (!$wordpressTemplate->getSetterProperty('setContentBlock')->isValueSet()) {
			$wordpressTemplate->getSetterProperty('setContentBlock')->setValue($content_block);
		}
		if (!$wordpressTemplate->getSetterProperty('setWebLibraryManager')->isValueSet()) {
			$wordpressTemplate->getSetterProperty('setWebLibraryManager')->setValue($moufManager->instanceExists("defaultWebLibraryManager"));
		}
		
		// Let's rewrite the MoufComponents.php file to save the component
		$moufManager->rewriteMouf();
	}
}
