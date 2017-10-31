<?php
return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'batch-send' => array(
                    'options' => array(
                        'route' => 'mailer send',
                        'help_text' => 'Send batch mails.',
                        'defaults' => array(
                            'action' => 'send',
                            'controller' => 'console',
                            '__NAMESPACE__' => 'Kofus\Mailer\Controller'
                        ),
                    )
                ),
            )
        )
    )
);