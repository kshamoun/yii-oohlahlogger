<?php
/**
 * OohLaLogger setup for yii logging
 *
 * @author: Kevin Shamoun <kevin@operationsolutions.com>
 * @license GPLv2
 *
 * @copyright Copyright &copy; 2008-2011 Operation Solutions LLC

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

 */
class OohLaLogger extends CLogRoute
{
	protected function processLogs($logs)
	{
		if ((!isset(Yii::app()->params['oohLaLogApiKey'])))
			throw new CException("Your api key is NOT set for oohLaLog. Make sure the variable 'oohLaLogApiKey' is set in your params in the config.");

		$key = Yii::app()->params['oohLaLogApiKey'];
		// if set to false then don't log
		if ($key === FALSE)
			return;

		$ollHost = 'api.oohlalog.com';
		$ollPath = '/api/logging/save.json';
		$ollPort = '80';
		$url = 'http://' . $ollHost . ':' . $ollPort . $ollPath . '?apiKey=' . $key;

		$text=array();
		foreach($logs as $log){
			$m = explode ( 'Stack trace:', $log[0] ,2 );
			$text = array('level'=> $log[1], 'category' => $log[2], 'timestamp'=> floor(microtime(true)*1000), 'hostName'=> gethostname(),'message'=> addcslashes($m[0],"'") ,'details'=> ( isset($m[1])? $m[1]: ''));
			$payload = json_encode(array('logs' => array($text)),JSON_HEX_APOS );
			$cmd = "curl -X POST -H 'Content-Type: application/json' -d '".$payload."' "."'".$url."' > /dev/null 2>&1 &";
			exec($cmd, $output, $exit);
		}
	}
}
?>