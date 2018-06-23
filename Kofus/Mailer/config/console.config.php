<?php
return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'run-batch' => array(
                    'options' => array(
                        'route' => 'batch <batch>',
                        'help_text' => 'Run any batch script by its ZF2 service name.',
                        'defaults' => array(
                            'action' => 'runBatch',
                            'controller' => 'console',
                            '__NAMESPACE__' => 'Kofus\System\Controller'
                        ),
                    )
                ),
                'import' => array(
                    'options' => array(
                        'route' => 'import subscribers <filename> <channel>',
                        'help_text' => 'Import subscribers from CSV file',
                        'defaults' => array(
                            'action' => 'import-subscribers',
                            'controller' => 'console',
                            '__NAMESPACE__' => 'Kofus\Mailer\Controller'
                        ),
                    )
                ),
                
            )
        )
    )
);