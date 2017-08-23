<?php
namespace Kofus\Mailer;

return array(
    
    'nodes' => array(
        	'available' => array(
        	    'MTMPL' => array(
        	        'label' => 'Mail Template',
        	        'entity' => 'Kofus\Mailer\Entity\TemplateEntity',
        	        'controllers' => array('Kofus\Mailer\Controller\Template', 'Kofus\Archive\Controller\Mail'),
        	        'form' => array(
        	        		'default' => array(
        	        				'fieldsets' => array(
        	        						'master' => array(
        	        								'class' => 'Kofus\Mailer\Form\Fieldset\Template\MasterFieldset',
        	        								'hydrator' => 'Kofus\Mailer\Form\Hydrator\Template\MasterHydrator'
        	        						),
        	        				)
        	        		)
        	        )
        	   ),
        	   'NEWSGROUP' => array(
        	       'label' => 'Newsgroup',
        	       'entity' => 'Kofus\Mailer\Entity\NewsgroupEntity',
        	       'controllers' => array('Kofus\Mailer\Controller\Newsgroup'),
        	       'form' => array(
        	       		'default' => array(
        	       				'fieldsets' => array(
        	       						'master' => array(
        	       								'class' => 'Kofus\Mailer\Form\Fieldset\Newsgroup\MasterFieldset',
        	       								'hydrator' => 'Kofus\Mailer\Form\Hydrator\Newsgroup\MasterHydrator'
        	       						),
        	       				)
        	       		)
        	       )
        	   ),
        	    'SUBSCR' => array(
        	    		'label' => 'Subscription',
        	    		'entity' => 'Kofus\Mailer\Entity\SubscriptionEntity',
        	    		'controllers' => array('Kofus\Mailer\Controller\Subscription'),
        	    		'form' => array(
        	    				'default' => array(
        	    						'fieldsets' => array(
        	    								'master' => array(
        	    										'class' => 'Kofus\Mailer\Form\Fieldset\Subscription\MasterFieldset',
        	    										'hydrator' => 'Kofus\Mailer\Form\Hydrator\Subscription\MasterHydrator'
        	    								),
        	    						)
        	    				)
        	    		)
        	    ),
        	     
    	
            )
        ),
    

);