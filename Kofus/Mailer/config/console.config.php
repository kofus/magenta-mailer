<?php
return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'batch-send' => array(
                    'options' => array(
                        'route' => 'mailer send <news> <channel>',
                        'help_text' => 'Send batch mails.',
                        'defaults' => array(
                            'action' => 'sendBatch',
                            'controller' => 'console',
                            '__NAMESPACE__' => 'Kofus\Mailer\Controller'
                        ),
                    )
                ),
            )
        )
    )
);