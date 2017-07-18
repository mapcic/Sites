

<?php
  if (!defined('_MACHINE_ON')) {
      define( '_MACHINE_ON', 1 );
  }
  
  include('framework.php');

  $db = new MachineDbSqlMysqli( new MachineConfig );
  
  $query = $db->getQuery()
    ->select('*')
    ->from($db->quoteName('#__salary_url'))
    ->where($db->quoteName('temp').' = '.$db->quote($_GET['code']));

  $result = $db->setQuery($query)->loadObject();

  if( !empty($result) || $result->uses != 0 ){
      return false;
  }

  $data = new stdClass();
    $data->id = $result->id;
    $data->url = $result->url;
    $data->temp = $result->temp;
    $data->uses = 1;

  $db->updateObject('#__salary_url', $data, 'id');

  $file = $result->url;
    
    if (!file_exists($file)){
      return false;
    }
    
    if (ob_get_level()){
      ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment, filename=salary.zip');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    
    readfile($file);
    unlink($file);
    
    return true;


  <?php
    if (!defined('_MACHINE_ON')) {
        define( '_MACHINE_ON', 1 );
    }
    
    include('framework.php');

  $db = new MachineDbSqlMysqli( new MachineConfig );
  
  $query = $db->getQuery()
    ->select('*')
    ->from($db->quoteName('#__salary_url'))
    ->where($db->quoteName('temp').' = '.$db->quote($_GET['code']));

  $result = $db->setQuery($query)->loadObject();

  if( !empty($result) || $result->uses != 0 ){
      return false;
  }

  $data = new stdClass();
    $data->id = $result->id;
    $data->url = $result->url;
    $data->temp = $result->temp;
    $data->uses = 1;

  $db->updateObject('#__salary_url', $data, 'id');

  $file = $result->url;
  if (file_exists($file)) {
    if (ob_get_level()) {
      ob_end_clean();
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    unlink($file);
  }
    
    
exit;