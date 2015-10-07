<?php
if (array_key_exists('sEcho', $_REQUEST)) {
      $result = array();
      $belovedColl = new \mementomei\BelovedColl($GLOBALS['db']);
      $belovedColl->loadAll($_REQUEST);
      $result['sEcho']=intval($_REQUEST['sEcho']);
      $request = $_REQUEST;
      unset($request['sSearch']);
      $result['iTotalRecords']=$belovedColl->countAll($request);
      $result['iTotalDisplayRecords']=$belovedColl->countAll($_REQUEST);
      $result['aaData']=array();
      $columns = $belovedColl->getColumns();
      foreach($belovedColl->getItems() as $key => $beloved) {
         $row=array();
         foreach($columns as $column) {
            $data = $beloved->getRawData($column);
            if ($column == 'actions') {
               $data = '<a class="actions modify" title="Modifica" href="?task=beloved&amp;action=edit&amp;id='.$beloved->getData('id').'">Modifica</a><a class="actions delete" title="Cancella" href="?task=beloved&amp;action=delete&amp;id='.$beloved->getData('id').'">Cancella</a>';
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
   $this->getTemplate()->setBlock('middle','administrator/beloved/edit.phtml');
   $this->getTemplate()->setBlock('footer','administrator/beloved/footer.phtml');  
   if (
            array_key_exists('xhrValidate', $_REQUEST) ||
            array_key_exists('submit', $_REQUEST)
      ) {
      if (!array_key_exists('description', $_REQUEST) ||$_REQUEST['description']=='') {
          $this->addValidationMessage('description','la denominazione è obbligatoria');
      }
      $date = new \DateTime();
      if (!array_key_exists('date_of_birth', $_REQUEST) ||$_REQUEST['date_of_birth']!='') {
          $dateOfBirth = $date->createFromFormat('Y-m-d',$_REQUEST['date_of_birth']);
          if(!is_object($dateOfBirth) || $dateOfBirth->format('Y-m-d') != $_REQUEST['date_of_birth']) {
               $this->addValidationMessage('date_of_birth','la data di nascita è errata (formato 1930-03-31)');
          }
      }
      if (!array_key_exists('date_of_death', $_REQUEST) ||$_REQUEST['date_of_death']!='') {
          $dateOfDeath = $date->createFromFormat('Y-m-d',$_REQUEST['date_of_death']);
          if(!is_object($dateOfDeath) || $dateOfDeath->format('Y-m-d') != $_REQUEST['date_of_death']) {
               $this->addValidationMessage('date_of_death','la data di nascita è errata (formato 2010-03-31)');
          }
      }
      if (
              isset($dateOfBirth) && is_object($dateOfBirth) && 
              isset($dateOfDeath) && is_object($dateOfDeath) &&
              $dateOfBirth > $dateOfDeath
        ) {
            $this->addValidationMessage('date_of_birth','la data di nascita è successiva alla data di decesso');
        }
            
      $beloved = new \mementomei\Beloved($GLOBALS['db']);
      if (array_key_exists('submit', $_REQUEST) && $this->formIsValid()) {
         $beloved->setData($_REQUEST);
         if (array_key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
            $beloved->update();
         } else {
            $beloved->insert();
         }
         header('Location: '.$GLOBALS['db']->config->baseUrl.'administrator.php?task=beloved');
         exit(); 
      }
   }
   break; 
case 'delete' :
   $beloved = new \mementomei\Beloved($GLOBALS['db']);
   if (array_key_exists('id', $_REQUEST) && $_REQUEST['id'] != '') {      
      $beloved->loadFromId($_REQUEST['id']);
      $beloved->delete();
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
         $beloved = new \mementomei\Beloved($GLOBALS['db']);
         $beloved->loadFromId($_REQUEST['id']);
         $beloved->setData($_REQUEST['value'], 'name');
         $beloved->update();
         $beloved->loadFromId($_REQUEST['id']);
         echo $beloved->getData('name');
         exit;
       }
   break;
default:
   $this->getTemplate()->setBlock('header','administrator/header.phtml'); 
   $this->getTemplate()->setBlock('middle','administrator/beloved/list.phtml');
   $this->getTemplate()->setBlock('footer','administrator/beloved/footer.phtml');  
break;
}