<?php
if (array_key_exists('sEcho', $_REQUEST)) {
      $result = array();
      $mementoColl = new \mementomei\memento\MementoColl($GLOBALS['db']);
      $mementoColl->loadAll($_REQUEST);
      $result['sEcho']=intval($_REQUEST['sEcho']);
      $request = $_REQUEST;
      unset($request['sSearch']);
      $result['iTotalRecords']=$mementoColl->countAll($request);
      $result['iTotalDisplayRecords']=$mementoColl->countAll($_REQUEST);
      $result['aaData']=array();
      $columns = $mementoColl->getColumns();
      foreach($mementoColl->getItems() as $key => $memento) {
         $row=array();
         foreach($columns as $column) {
            $data = $memento->getRawData($column);
            if ($column == 'actions') {
               $data = '<a class="actions modify" title="Modifica" href="?task=memento&amp;action=edit&amp;code='.$memento->getData('code').'">Modifica</a><a class="actions delete" title="Cancella" href="?task=memento&amp;action=delete&amp;code='.$memento->getData('code').'">Cancella</a>';
            } 
            $row[] = $data;     
         }
         $result['aaData'][]=$row;
      }
      header('Content-Type: application/json');
      echo json_encode($result);
      exit;
}
if (!array_key_exists('action',$_REQUEST)) {
   $_REQUEST['action']=null;
}
switch ($_REQUEST['action']) {
case 'edit':
   $this->getTemplate()->setBlock('header','administrator/header.phtml'); 
   $this->getTemplate()->setBlock('middle','administrator/memento/edit.phtml');
   $this->getTemplate()->setBlock('footer','administrator/memento/footer.phtml');  
   if (
            array_key_exists('xhrValidate', $_REQUEST) ||
            array_key_exists('submit', $_REQUEST)
      ) {
      if (!array_key_exists('name', $_REQUEST) ||$_REQUEST['name']=='') {
          $this->addValidationMessage('name','Il nome è obbligatorio');
      }
      if (!array_key_exists('city', $_REQUEST) ||$_REQUEST['city']=='') {
          $this->addValidationMessage('city','La città è obbligatoria');
      } 
      $memento = new \mementomei\memento\memento($GLOBALS['db']);
      if (array_key_exists('submit', $_REQUEST) && $this->formIsValid()) {
         $memento->setData($_REQUEST);
         if (array_key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
            $memento->update();
         } else {
            $memento->insert();
         }
         header('Location: '.$GLOBALS['db']->config->baseUrl.'administrator.php?task=memento');
         exit(); 
      }
   }
   break; 
case 'delete' :
   $memento = new \mementomei\memento\memento($GLOBALS['db']);
   if (array_key_exists('id', $_REQUEST) && $_REQUEST['id'] != '') {      
      $memento->loadFromId($_REQUEST['id']);
      $memento->delete();
   }
   exit;
   break;
case 'jeditable' :
   if (
           array_key_exists('id',$_REQUEST) &&
           is_numeric($_REQUEST['id']) &&
           array_key_exists('value',$_REQUEST) &&
           strlen($_REQUEST['value']) > 1
       ) {
         $memento = new \mementomei\memento\memento($GLOBALS['db']);
         $memento->loadFromId($_REQUEST['id']);
         $memento->setData($_REQUEST['value'], 'name');
         $memento->update();
         $memento->loadFromId($_REQUEST['id']);
         echo $memento->getData('name');
         exit;
       }
   break;
default:
   $this->getTemplate()->setBlock('header','administrator/header.phtml'); 
   $this->getTemplate()->setBlock('middle','administrator/memento/list.phtml');
   $this->getTemplate()->setBlock('footer','administrator/memento/footer.phtml');  
break;
}