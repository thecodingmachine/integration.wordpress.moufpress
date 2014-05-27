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
		$content_block = InstallUtils::getOrCreateInstance('block.content', 'Mouf\\Html\\HtmlElement\\HtmlBlock', $moufManager);
		
		// Let's bind instances together.
		if (!$wordpressTemplate->getSetterProperty('setContentBlock')->isValueSet()) {
			$wordpressTemplate->getSetterProperty('setContentBlock')->setValue($content_block);
		}
		if (!$wordpressTemplate->getSetterProperty('setWebLibraryManager')->isValueSet()) {
			$wordpressTemplate->getSetterProperty('setWebLibraryManager')->setValue($moufManager->getInstanceDescriptor("defaultWebLibraryManager"));
		}
		
		// Lets replace the rightsService with Moufpress' UserService
		if ($moufManager->instanceExists("userService")) {
			// Let's remove the default defaultWebLibraryRenderer :)
			$moufManager->removeComponent("userService");
		}
		$wordpressUserService = $moufManager->createInstance("Mouf\\Integration\\Wordpress\\Moufpress\\MoufpressUserService");
		$wordpressUserService->setName('userService');
		
		// Lets replace the rightsService with Moufpress' RightsService
		if ($moufManager->instanceExists("rightsService")) {
			// Let's remove the default defaultWebLibraryRenderer :)
			$moufManager->removeComponent("rightsService");
		}
		$wordpressRightsService = $moufManager->createInstance("Mouf\\Integration\\Wordpress\\Moufpress\\MoufpressRightService");
		$wordpressRightsService->setName('rightsService');
		
		// Let's create the instances.
		$moufpress = InstallUtils::getOrCreateInstance('moufpress', 'Mouf\\Integration\\Wordpress\\Moufpress\\Moufpress', $moufManager);
		
		// Let's bind instances together.
		if (!$moufpress->getConstructorArgumentProperty('wordpressTemplate')->isValueSet()) {
			$moufpress->getConstructorArgumentProperty('wordpressTemplate')->setValue($wordpressTemplate);
		}
		
		if (!$moufpress->getConstructorArgumentProperty("cacheService")->isValueSet()) {
			if (!$moufManager->instanceExists("splashCacheApc")) {
				$splashCacheApc = $moufManager->createInstance("Mouf\\Utils\\Cache\\ApcCache");
				$splashCacheApc->setName("splashCacheApc");
		
				if (!$moufManager->instanceExists("splashCacheFile")) {
					$splashCacheFile = $moufManager->createInstance("Mouf\\Utils\\Cache\\FileCache");
					$splashCacheFile->setName("splashCacheFile");
					$splashCacheFile->getProperty("cacheDirectory")->setValue("splashCache/");
				} else {
					$splashCacheFile = $moufManager->getInstanceDescriptor("splashCacheApc");
				}
		
				if (isset($constants['SECRET'])) {
					$splashCacheFile->getProperty('prefix')->setValue('SECRET')->setOrigin('config');
				}
		
				$splashCacheApc->getProperty("fallback")->setValue($splashCacheFile);
					
			} else {
				$splashCacheApc = $moufManager->getInstanceDescriptor("splashCacheApc");
			}
			
			if (isset($constants['SECRET'])) {
				$splashCacheApc->getProperty('prefix')->setValue('SECRET')->setOrigin('config');
			}
		
			$moufpress->getConstructorArgumentProperty("cacheService")->setValue($splashCacheApc);
		}
		
		// Let's rewrite the MoufComponents.php file to save the component
		$moufManager->rewriteMouf();
	}
}
