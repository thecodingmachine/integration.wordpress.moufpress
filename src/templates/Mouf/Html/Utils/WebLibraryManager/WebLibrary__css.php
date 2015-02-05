<?php
use Mouf\Html\Utils\WebLibraryManager\WebLibrary;
/* @var $object WebLibrary  */

$moufPress = \Mouf::getMoufpress();
$name = $moufPress->registerWordpressLib($object);
wp_enqueue_style($name);