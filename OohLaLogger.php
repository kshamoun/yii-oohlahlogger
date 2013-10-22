<?php
/**
 * OohLaLogger setup for yii logging
 *
 * @author: Kevin Shamoun <kevin@operationsolutions.com>
 * @license GPLv2
 *
 * @copyright Copyright &copy; 2008-2011 Operation Solutions LLC

To configure please setup main.php config file:

Setup in main.php config file:

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

 */
class OohLaLogger extends CLogRoute
{
	public $skip_if_yiidebug_on = true;
	public $oohLaLogApiKey = false;

	protected function processLogs($logs)
	{
		if(YII_DEBUG AND $this->skip_if_yiidebug_on === true);
			return;

		// if set to false then don't log
		if ($this->oohLaLogApiKey === FALSE)
			return;

		$url = 'http://api.oohlalog.com:80/api/logging/save.json?apiKey='.$this->oohLaLogApiKey;

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