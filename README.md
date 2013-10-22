yii-oohlahlogger
=================

Yii debugging to oohlalog.com

To configure please setup as shown below.  Adding the API Key to your params
array is somewhat optional as you can simply replace the key in 2 places at
the beginning of the class.  I set it up this way because I utilized
boilerplate similar to http://yiinitializr.2amigos.us/


Setup in main.php config file:

	'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
					array(
							//  change the class location to suit your setup
							'class'=>'common.extensions.OohLaLogger',
							// for levels see: http://www.yiiframework.com/doc/guide/1.1/en/topics.logging
							'levels'=>'info,error,warning',
					),
			)
	),


Setup in your params array:
If you set the key to FALSE then it won't log (good for dev/test environments so they don't fill your logs)
'oohLaLogApiKey' => 'YOUR_API_KEY_HERE',
