<?php
class Config
{
	public static function get($setting=null)
	{
		
		//if(isset($setting))
		{
			if(file_exists(FRAMEWORK_CONFIG.'config.php')){
				$configJson=(file_get_contents(FRAMEWORK_CONFIG.'config.php'));
				print '<pre>';
				print_r($configJson);exit;
			}
		}
	}
}
?>