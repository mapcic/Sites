<?php
if (!defined('_MACHINE_ON')) {
	define('_MACHINE_ON', 1);
}
include('framework.php');

$db = new MachineDbSqlMysqli(new MachineConfig);
$query = $db->getQuery()
	->select('*')
	->from($db->quoteName('#__vkmachine_domains'))
	->where($db->quoteName('domain').' = '.$db->quote($domain));
$db->setQuery($query);
$client = $db->loadObject();

$query = $db->getQuery()
	->select('COUNT('.$db->quoteName('id').')')
	->from($db->quoteName('#__vkmachine_domains'));
$useVkMachine = $db->setQuery($query)->loadResult(); 

$resp = array(
	'num' => $useVkMachine
);

echo $_GET['callback']."(".json_encode($resp).");";
?>