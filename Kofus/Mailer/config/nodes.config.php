<?php
namespace Kofus\Mailer;

return array(
    
    'nodes' => array(
        'enabled' => array(
            'NCH', 'SCB', 'SCP', 'NS', 'MJ', 'ML'
        ),
        'available' => array(
            'ML' => array(
                'label' => 'Mail',
                'label_pl' => 'Mails',
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
                            )
                        )
                    ),
                ),
            ),
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

            'NCH' => array(
                'label' => 'News Channel',
                'label_pl' => 'News Channels',
                'entity' => 'Kofus\Mailer\Entity\NewsChannelEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\NewsChannel'
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\NewsChannel\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\NewsChannel\MasterHydrator'
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
                        )
                    ),
                    
                    
                )
            ),
            'SCB' => array(
                'label' => 'News Subscriber',
                'label_pl' => 'News Subscribers',
                'entity' => 'Kofus\Mailer\Entity\NewsSubscriberEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\NewsSubscriber'
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\NewsSubscriber\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\NewsSubscriber\MasterHydrator'
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
                'label' => 'News Subscription',
                'label_pl' => 'News Subscriptions',
                'entity' => 'Kofus\Mailer\Entity\NewsSubscriptionEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\NewsSubscription'
                ),
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