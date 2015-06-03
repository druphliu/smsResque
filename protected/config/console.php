<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.components.sms.*',
    ),
	// application components
	'components'=>array(
        'resque' => array(
            'class' => 'application.components.yii-resque.RResque',
            'server' => 'localhost',     // Redis server address
            'port' => '6379',            // Redis server port
            'database' => 0,             // Redis database number
            'password' => '',            // Redis password auth, set to '' or null when no auth needed
            'includeFiles' => array(),    // Absolute path of files that will be included when initiate queue
            'loghandler' => 'RotatingFile', // Monolog handler type without "handler"
            'logtarget' => '/var/log/mylog' // Target log file or configuration (please refer to logging section)
        ),
		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),

	),
);
