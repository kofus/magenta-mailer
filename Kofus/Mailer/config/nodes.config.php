<?php
namespace Kofus\Mailer;

return array(
    
    'nodes' => array(
        'enabled' => array(
            'NCH', 'SCB', 'SCP'
        ),
        'available' => array(
            'MTMPL' => array(
                'label' => 'Mail Template',
                'entity' => 'Kofus\Mailer\Entity\TemplateEntity',
                'controllers' => array(
                    'Kofus\Mailer\Controller\Template',
                    'Kofus\Archive\Controller\Mail'
                ),
                'form' => array(
                    'default' => array(
                        'fieldsets' => array(
                            'master' => array(
                                'class' => 'Kofus\Mailer\Form\Fieldset\Template\MasterFieldset',
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Template\MasterHydrator'
                            )
                        )
                    )
                )
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
                    )
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