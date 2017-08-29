<?php
namespace Kofus\Mailer;

return array(
    
    'controllers' => array(
        'invokables' => array(
            'Kofus\Mailer\Controller\NewsChannel' => 'Kofus\Mailer\Controller\NewsChannelController',
            'Kofus\Mailer\Controller\NewsSubscriber' => 'Kofus\Mailer\Controller\NewsSubscriberController',
            'Kofus\Mailer\Controller\News' => 'Kofus\Mailer\Controller\NewsController',
        )
    ),
    
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php'
            )
        )
    ),
    
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . str_replace('\\', '/', __NAMESPACE__) . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    
    'router' => array(
        'routes' => array(
            'kofus_mailer' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/:language/' . KOFUS_ROUTE_SEGMENT . '/mailer/:controller/:action[/:id[/:id2]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'language' => '[a-z][a-z]'
                    ),
                    'defaults' => array(
                        'language' => 'de',
                        '__NAMESPACE__' => 'Kofus\Mailer\Controller'
                    )
                ),
                'may_terminate' => true
            ),
        )
    ),
    
    'service_manager' => array(
        'invokables' => array(
            'KofusMailerService' => 'Kofus\Mailer\Service\MailerService'
        )
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'Mailer' => __DIR__ . '/../view'
        ),
        'controller_map' => array(
            'Kofus\Mailer' => true
        ),
        'module_layouts' => array(
            'Kofus\\Mailer' => 'kofus/layout/admin'
        )
    ),
    
    'view_helpers' => array(
        'invokables' => array(
            'mailer' => 'Kofus\Mailer\View\Helper\MailerHelper',
            'imageBase64Source' => 'Kofus\Mailer\View\Helper\ImageBase64SourceHelper'
        )
    ),
    
    'controller_plugins' => array(
        'invokables' => array(
            'mailer' => 'Kofus\Mailer\Controller\Plugin\MailerPlugin'
        )
    )
    /*
 * 'user' => array(
 * 'controller_mappings' => array(
 * 'Kofus\Mailer\Controller\Index' => 'System'
 * )
 * )
 */
);