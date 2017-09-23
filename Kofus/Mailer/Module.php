<?php

namespace Kofus\Mailer;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Console\Adapter\AdapterInterface as Console;


class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
    	$config = array();
    	foreach (glob(__DIR__ . '/config/*.config.php') as $filename)
    		$config = array_merge_recursive($config, include $filename);
    	return $config;
    }
    
    /**
     * Assembles console help texts as provided in console router config (param "help_text")
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        $usage = array();
        $config = $this->getConfig();
        if (isset($config['console']['router']['routes'])) {
            foreach ($config['console']['router']['routes'] as $route) {
                if (isset($route['options']['help_text']))
                    $usage[$route['options']['route']] = $route['options']['help_text'];
            }
        }
        return $usage;
    }
    

    
}
