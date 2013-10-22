yii-oohlahlogger
=================

Yii debugging to oohlalog.com

To configure please setup main.php config file:

	'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
					array(
							//  change the class location to suit your setup
							'class'=>'common.extensions.OohLaLogger',
							// for levels see: http://www.yiiframework.com/doc/guide/1.1/en/topics.logging
							'levels'=>'info,error,warning',
							// if you have YII_DEBUG set to true and this set to true it will skip logging
							'skip_if_yiidebug_on' => true,
							// Put your api key here
							'oohLaLogApiKey' => 'YOUR_API_KEY_HERE'
					),
			)
	),