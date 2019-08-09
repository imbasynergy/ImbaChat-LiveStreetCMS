<?php
/**
 * Конфиг плагина
 */
$config['components'] = array('initjs');

$config['data']['login'] = 'root';
$config['data']['password'] = '123';
$config['data']['dev_id'] = '26';
$config['data']['in_password'] = '';


$config['$root$']['router']['page']['imba_chat_widget'] = 'PluginImbaChatWidget_ActionIndex';
return $config;