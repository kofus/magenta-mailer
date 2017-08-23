<?php
return array(
	'cron' => array(
	   'available' => array(
	       'dispatch_mails' => array(
	           'job' => array('name' => 'Application\Cron\Job\MailJob'),
	           'scheduler' => array('options' => array(
	               'interval' => '1 hour',
	               'batch_size' => 2
	           ))
	   	
	       )
		
	)
)
);