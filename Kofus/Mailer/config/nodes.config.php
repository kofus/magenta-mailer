<?php
namespace Kofus\Mailer;

return array(
    
    'nodes' => array(
        'enabled' => array(
            'NCH', 
            'SCB', 
            'SCP', // 'NS', 'MJ', 
            'ML'
        ),
        'available' => array(
            'ML' => array(
                'label' => 'Nachricht',
                'label_pl' => 'Nachrichten',
                'entity' => 'Kofus\Mailer\Entity\MailEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\Mail'
                ),
                'navigation' => array(
                    'list' => array(
                        'add' => array(
                            'icon' => 'glyphicon glyphicon-plus',
                            'label' => 'Add',
                            'route' => 'kofus_system',
                            'controller' => 'node',
                            'action' => 'add',
                            'params' => array(
                                'id' => 'ML'
                            )
                        )
                    )
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Mail\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Mail\MasterHydrator'
                            ),
                            'addresses' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Mail\AddressesFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Mail\AddressesHydrator'
                            ),
                            'dispatch' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Mail\DispatchFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Mail\DispatchHydrator'
                            ),
                            
                        )
                    ),
                ),
            ),
            /*
            'MJ' => array(
                'label' => 'Job',
                'label_pl' => 'Jobs',
                'entity' => 'Kofus\Mailer\Entity\JobEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\Job'
                ),
                'navigation' => array(
                    'list' => array(
                        'add' => array(
                            'icon' => 'glyphicon glyphicon-plus',
                            'label' => 'Add',
                            'route' => 'kofus_system',
                            'controller' => 'node',
                            'action' => 'add',
                            'params' => array(
                                'id' => 'MJ'
                            )
                        )
                    )
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Job\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Job\MasterHydrator'
                            )
                        )
                    ),
                ),
                
            ),
            'NS' => array(
                'label' => 'News',
                'label_pl' => 'News',
                'entity' => 'Kofus\Mailer\Entity\NewsEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\News'
                ),
                'navigation' => array(
                    'list' => array(
                        'add' => array(
                            'icon' => 'glyphicon glyphicon-plus',
                            'label' => 'Add',
                            'route' => 'kofus_system',
                            'controller' => 'node',
                            'action' => 'add',
                            'params' => array(
                                'id' => 'NS'
                            )
                        )
                    )
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\News\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\News\MasterHydrator'
                            )
                        )
                    )
                ),
                'search_documents' => array(
                    'Kofus\Mailer\Lucene\Document\NewsDocument'
                ),
                
                
                
            ),
            */
            'NCH' => array(
                'label' => 'Kanal',
                'label_pl' => 'Kanäle',
                'entity' => 'Kofus\Mailer\Entity\ChannelEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\Channel'
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Channel\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Channel\MasterHydrator'
                            )
                        )
                    )
                ),
                'navigation' => array(
                    'list' => array(
                        'add' => array(
                            'icon' => 'glyphicon glyphicon-plus',
                            'label' => 'Add',
                            'route' => 'kofus_system',
                            'controller' => 'node',
                            'action' => 'add',
                            'params' => array(
                                'id' => 'NCH'
                            )
                        )
                    ),
                    'view' => array(
                        'edit' => array(
                            'icon' => 'glyphicon glyphicon-pencil',
                            'label' => 'Edit',
                            'route' => 'kofus_system',
                            'controller' => 'node',
                            'action' => 'edit',
                            'params' => array(
                                'id' => '{node_id}'
                            )
                        ),
                        'truncate' => array(
                            'label' => 'Leeren',
                            'route' => 'kofus_mailer',
                            'controller' => 'channel',
                            'action' => 'truncate',
                            'params' => array(
                                'id' => '{node_id}'
                            )
                        ),
                        'csv' => array(
                            'icon' => 'glyphicon glyphicon-export',
                            'label' => 'CSV',
                            'route' => 'kofus_mailer',
                            'controller' => 'channel',
                            'action' => 'csv',
                            'params' => array(
                                'id' => '{node_id}'
                            )
                        ),
                        
                    ),
                    
                    
                )
            ), 
            'SCB' => array(
                'label' => 'Abonnent',
                'label_pl' => 'Abonnenten',
                'entity' => 'Kofus\Mailer\Entity\SubscriberEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\Subscriber'
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Subscriber\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Subscriber\MasterHydrator'
                            )
                        )
                    )
                ),
                'navigation' => array(
                    'list' => array(
                        'add' => array(
                            'icon' => 'glyphicon glyphicon-plus',
                            'label' => 'Add',
                            'route' => 'kofus_system',
                            'controller' => 'node',
                            'action' => 'add',
                            'params' => array(
                                'id' => 'SCB'
                            )
                        )
                    ),
                    'view' => array(
                        'edit' => array(
                            'icon' => 'glyphicon glyphicon-pencil',
                            'label' => '',
                            'route' => 'kofus_system',
                            'controller' => 'node',
                            'action' => 'edit',
                            'params' => array(
                                'id' => '{node_id}'
                            )
                        )
                    ),
                )
            ),
            'SCP' => array(
                'label' => 'Abonnement',
                'label_pl' => 'Abonnements',
                'entity' => 'Kofus\Mailer\Entity\SubscriptionEntity',
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Subscription\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Subscription\MasterHydrator'
                            )
                        )
                    )
                )
            ),
        )
    )

);