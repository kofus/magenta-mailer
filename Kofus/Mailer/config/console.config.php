<?php
return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                '_run-batch' => array(
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
            )
        )
    )
);