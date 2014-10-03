<?php
if (array_key_exists('sEcho', $_REQUEST)) {
      $result = array();
      $userColl = new \login\user\UserColl($GLOBALS['db']);
      $userColl->loadAll($_REQUEST);
      $result['sEcho']=intval($_REQUEST['sEcho']);
      $result['iTotalRecords']=$userColl->count();
      $result['iTotalDisplayRecords']=$userColl->countAll();
      $result['aaData']=array();
      $columns = $userColl->getColumns();
      foreach($userColl->getItems() as $key => $user) {
         $row=array();
         foreach($columns as $column) {
            $data = $user->getRawData($column);
            if ($column == 'active') {
               $checked='';
               if ($data ==1)
                  $checked='checked="checked" ';
               $data = '<input '.$checked.'type="checkbox" name="active">';
            } 
            $row[] = $data;     
         }
         $result['aaData'][]=$row;
      }
      header('Content-Type: application/json');
      echo json_encode($result);
      exit;
}
$this->getTemplate()->setBlock('middle','administrator/user/list.phtml');
$this->getTemplate()->setBlock('footer','administrator/user/footer.phtml');  