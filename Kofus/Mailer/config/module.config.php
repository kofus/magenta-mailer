<?php
namespace Kofus\Mailer;

return array(
    
    'controllers' => array(
        'invokables' => array(
            'Kofus\Mailer\Controller\Channel' => 'Kofus\Mailer\Controller\ChannelController',
            'Kofus\Mailer\Controller\Mail' => 'Kofus\Mailer\Controller\MailController',
            'Kofus\Mailer\Controller\Subscriber' => 'Kofus\Mailer\Controller\SubscriberController',
            'Kofus\Mailer\Controller\Console' => 'Kofus\Mailer\Controller\ConsoleController',
            'Kofus\Mailer\Controller\Newsletter' => 'Kofus\Mailer\Controller\NewsletterController',
        )
    ),
    
    'user' => array(
        'controller_mappings' => array(
            'Kofus\Mailer\Controller\Frontend' => 'Frontend',
            'Kofus\Mailer\Controller\Console' => 'Console',
            'Kofus\Mailer\Controller\Newsletter' => 'Frontend'
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
            ),
        )
    ),
    
    'service_manager' => array(
        'invokables' => array(
            'KofusMailerService' => 'Kofus\Mailer\Service\MailerService',
            'KofusMailerErrorListener' => 'Kofus\Mailer\Listener\ErrorListener',
            'KofusMailerSendMailBatch' => 'Kofus\Mailer\Batch\SendMailBatch'
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
            'Kofus\Mailer\Controller\ChannelController' => 'kofus/layout/admin',
            'Kofus\Mailer\Controller\SubscriberController' => 'kofus/layout/admin',
            'Kofus\Mailer\Controller\NewsController' => 'kofus/layout/admin',
            'Kofus\Mailer\Controller\JobController' => 'kofus/layout/admin',
            'Kofus\Mailer\Controller\MailController' => 'kofus/layout/admin',
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