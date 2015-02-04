<?php
/*
 * This file is part of the Moufpress package.
 *
 * (c) 2014 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
namespace Mouf\Integration\Wordpress\Moufpress;

use Mouf\Mvc\Splash\Services\SplashUtils;
use Mouf\MoufManager;
use Mouf\Reflection\MoufReflectionClass;
use Mouf\Mvc\Splash\Services\FilterUtils;
use Mouf\Reflection\MoufReflectionMethod;
use Mouf\Mvc\Splash\Services\SplashRequestContext;
use Mouf\Mvc\Splash\Utils\SplashException;
use Mouf\Utils\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Mouf\Mvc\Splash\HtmlResponse;


/**
 * This class is the root class of Moufpress. It is in charge of mapping Splash MVC controllers to the
 * Wordpress routing system.
 * 
 * @author David Négrier <d.negrier@thecodingmachine.com>
 */
class Moufpress {
	/**
	 * A pointer to the wordpressTemplate.
	 * @var WordpressTemplate
	 */
	private $wordpressTemplate;
	
	/**
	 * The cache service that will be used to store routes.
	 * 
	 * @var CacheInterface
	 */
	private $cacheService;
	
	/**
	 * 
	 * @param WordpressTemplate $wordpressTemplate A pointer to the wordpressTemplate.
	 * @param CacheInterface $cacheService The cache service that will be used to store routes.
	 */
	public function __construct(WordpressTemplate $wordpressTemplate, CacheInterface $cacheService = null) {
		$this->wordpressTemplate = $wordpressTemplate;
		$this->cacheService = $cacheService;
		
		add_action('wp_router_generate_routes', array($this, 'generate_routes'), 10, 1);
		
		add_action( 'widgets_init', function(){
			register_widget( 'Mouf\\Integration\\Wordpress\\Moufpress\\MoufpressWidget' );
		});
	}
	
	public function generate_routes( $router ) {
		/* @var $router \WP_Router */
		$routes = $this->getRoutesWithCache();
		foreach ($routes as $i=>$route) {
			$router->add_route('moufpress_route_'.$i, $route);
		} 
		
		/*$router->add_route('moufpress-sample', array(
				'path' => '^moufpress/(.*?)$',
				'query_vars' => array(
						'sample_argument' => 1,
				),
				'page_callback' => array($this, 'sample_callback'),
				'page_arguments' => array('sample_argument'),
				'access_callback' => TRUE,
				'title' => 'WP Router Sample Page',
				//'template' => false
		));*/
	}

	/*public function sample_callback( $argument ) {
		echo '<p>Welcome to the WP Router sample page. You can find the code that generates this page in '.__FILE__.'</p>';
		echo '<p>This page helpfully tells you the value of the <code>sample_argument</code> query variable: '.esc_html($argument).'</p>';
	}*/
	
	
	
	
	/**
	 * Returns the list of routes as an array of arrays.
	 * Uses the cache mechanism if available
	 * 
	 * @throws SplashException
	 * @return array<array>
	 */
	public function getRoutesWithCache() {
		if ($this->cacheService == null) {
			// Retrieve the split parts
			return $this->getRoutesWithoutCache();
		} else {
			$routes = $this->cacheService->get("splashWordpressRoutes");
			if ($routes == null) {
				// No value in cache, let's get the URL nodes
				$routes = $this->getRoutesWithoutCache();
				$this->cacheService->set("splashWordpressRoutes", $routes);
			}
			return $routes;
		}
	}
	
	
	
	/**
	 * Returns the list of routes as an array of arrays.
	 * Bypasses the cache mechanism.
	 * 
	 * @throws SplashException
	 * @return array<array>
	 */
	public function getRoutesWithoutCache() {
		$urlsList = SplashUtils::getSplashUrlManager()->getUrlsList(false);
		
		$items = array();
		
		foreach ($urlsList as $urlCallback) {
			/* @var $urlCallback SplashCallback */
				
			$url = $urlCallback->url;
			// remove trailing slash
			$url = rtrim($url, "/");
				
			$title = null;
			if ($urlCallback->title !== null) {
				$title = $urlCallback->title ;
			}
				
			
			//////////////// Let's analyze the URL for parameter ////////////////////
			$trimmedUrl = trim($url, '/');
			$urlParts = explode("/", $trimmedUrl);
			$urlPartsNew = array();
			$parametersList = array();
                        // We will store the number of parameters for a URL
                        $nbParam = 0;
				
			for ($i=0; $i<count($urlParts); $i++) {
				$urlPart = $urlParts[$i];
				if (strpos($urlPart, "{") === 0 && strpos($urlPart, "}") === strlen($urlPart)-1) {
                                        $nbParam+=1;
					// Parameterized URL element
					$varName = substr($urlPart, 1, strlen($urlPart)-2);
						
					$parametersList[$varName] = $i;
					$urlPartsNew[] = '([^/]*?)';
				} else {
					$urlPartsNew[] = $urlPart;
				}
			}
				
			// Let's rewrite the URL, but replacing the {var} parameters with a regexp wildcard
			$url = '^'.implode('/', $urlPartsNew).'$';
			///////////////// End URL analysis ////////////////////
				
			$httpMethods = $urlCallback->httpMethods;
			if (empty($httpMethods)) {
				$httpMethods["default"] = 'moufpress_execute_action';
			} else {
				foreach ($httpMethods as $httpMethod) {
					$httpMethods[strtoupper($httpMethod)] = 'moufpress_execute_action';
				}
			}
                        
			foreach ($httpMethods as $httpMethod) {
				$item= array(
						'path' => $url,
						
						'page_callback' => $httpMethods,
						// First argument passed to execute_action as the instance name, second argument is the method.
						'page_arguments' => array($urlCallback->controllerInstanceName, $urlCallback->methodName, $parametersList, $urlCallback->parameters, $urlCallback->filters),
						'access_callback' => TRUE,
                                                 // We store the number of parameters for an item
                                                'nbParam' => $nbParam
						//'page arguments' => array(array($httpMethod => array("instance"=>$urlCallback->controllerInstanceName, "method"=>$urlCallback->methodName, "urlParameters"=>$parametersList))),
				);
				
				if ($title) {
					$item['title'] = $title;
				}
				
				$items[] = $item;
				
			}
				
		}
                
                /* 
                 * We sort the tableOfURLS with the one with the fewer parameters coming first
                 */
                usort($items, function(array $a, array $b) {
                    return $a['nbParam'] - $b['nbParam'];
                });
		return $items;
	}
	
	public function executeAction($instanceName, $method, $urlParameters, $parameters, $filters) {
		$request = Request::createFromGlobals();
		
		$controller = MoufManager::getMoufManager()->get($instanceName);
		
		if (method_exists($controller,$method)) {
			$requestParts = array();
			if ($urlParameters) {
				$pathinfo = isset( $_SERVER['PATH_INFO'] ) ? $_SERVER['PATH_INFO'] : '';
				list( $pathinfo ) = explode( '?', $pathinfo );
				$pathinfo = str_replace( "%", "%25", $pathinfo );
				list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );
				$home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
				
				// Trim path info from the end and the leading home path from the
				// front.
				$req_uri = str_replace($pathinfo, '', $req_uri);
				$req_uri = trim($req_uri, '/');
				$req_uri = preg_replace("|^$home_path|i", '', $req_uri);
				$req_uri = trim($req_uri, '/');
				$requestParts = explode('/', $req_uri);
			}
			
			
			$context = new SplashRequestContext($request);
			$context->setUrlParameters(array_map(function($itemPos) use ($requestParts) { return $requestParts[$itemPos]; }, $urlParameters));
		
			/****/
			$args = array();
			foreach ($parameters as $paramFetcher) {
				/* @var $param SplashParameterFetcherInterface */
				$args[] = $paramFetcher->fetchValue($context);
			}
		
			// Handle action__GET or action__POST method (for legacy code).
			if(method_exists($controller, $method.'__'.$_SERVER['REQUEST_METHOD'])) {
				$method = $method.'__'.$_SERVER['REQUEST_METHOD'];
			}
		
		
			// Apply filters
			for ($i=count($filters)-1; $i>=0; $i--) {
				$filters[$i]->beforeAction();
			}

			header_remove('Expires');
			header_remove('Cache-Control');
			header_remove('Pragma');
			
			$response = SplashUtils::buildControllerResponse(
					function() use ($controller, $method, $args){
						return call_user_func_array(array($controller,$method), $args);
					}
			);
			
			$wordpressTemplate = $this->wordpressTemplate;
			
			ob_start();
			if ($response instanceof HtmlResponse) {
				$htmlElement = $response->getHtmlElement();
				if ($htmlElement instanceof WordpressTemplate) {
					$htmlElement->toHtml();
					$htmlElement->getWebLibraryManager()->toHtml();
					$htmlElement->getContentBlock()->toHtml();
				} else {
					$response->send();
				}
			}else{
				$response->sendHeaders();
				$response->sendContent();
				if ($wordpressTemplate->isDisplayTriggered()) {
					$wordpressTemplate->getWebLibraryManager()->toHtml();
					$wordpressTemplate->getContentBlock()->toHtml();
				}
			}
			$result = ob_get_clean();
			
			if ($wordpressTemplate->isDisplayTriggered()) {
				$title = $wordpressTemplate->getTitle();
				if ($title) {
					$posts = get_posts(array(
							'post_type' => \WP_Router_Page::POST_TYPE,
							'post_status' => 'publish',
							'posts_per_page' => 1,
					));
					if ( $posts ) {
						$page_post_id = $posts[0]->ID;
					} else {
						$page_post_id = null;
					}
					 
					add_filter('the_title', function($previousTitle, $postId = null) use ($title, $page_post_id) {
						if (in_the_loop() || $page_post_id == $postId) {
							return $title;
						}
						return $previousTitle;
					}, 11);
					add_filter('wp_title', function() use ($title) {
						return $title;
					}, 11);
				}
					
			}
			
		
			foreach ($filters as $filter) {
				$filter->afterAction();
			}
		
			// Now, let's see if we must output everything in the template or out the template.
		
			if ($wordpressTemplate->isDisplayTriggered()) {
				echo $result;
			} else {
				echo $result;
				exit;
			}
		
		} else {
			global $wp_query;
			$wp_query->set_404();
		}
		
	}
	
}
