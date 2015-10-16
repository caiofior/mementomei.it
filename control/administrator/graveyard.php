<?php
if (array_key_exists('sEcho', $_REQUEST)) {
      $result = array();
      $graveyardColl = new \mementomei\agency\GraveyardColl($GLOBALS['db']);
      $graveyardColl->loadAll($_REQUEST);
      $result['sEcho']=intval($_REQUEST['sEcho']);
      $request = $_REQUEST;
      unset($request['sSearch']);
      $result['iTotalRecords']=$graveyardColl->countAll($request);
      $result['iTotalDisplayRecords']=$graveyardColl->countAll($_REQUEST);
      $result['aaData']=array();
      $columns = $graveyardColl->getColumns();
      foreach($graveyardColl->getItems() as $key => $graveyard) {
         $row=array();
         foreach($columns as $column) {
            $data = $graveyard->getRawData($column);
            if ($column == 'actions') {
               $data = '<a class="actions modify" title="Modifica" href="?task=beloved&amp;action=edit&amp;id='.$graveyard->getData('id').'">Modifica</a><a class="actions delete" title="Cancella" href="?task=beloved&amp;action=delete&amp;id='.$graveyard->getData('id').'">Cancella</a>';
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
            
      $graveyard = new \mementomei\Beloved($GLOBALS['db']);
      if (array_key_exists('submit', $_REQUEST) && $this->formIsValid()) {
         $graveyard->setData($_REQUEST);
         $graveyard->setBeloving($_REQUEST['beloving']);
         if (array_key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
            $graveyard->update();
         } else {
            $graveyard->insert();
         }
         header('Location: '.$GLOBALS['db']->config->baseUrl.'administrator.php?task=beloved');
         exit(); 
      }
   }
   break; 
case 'delete' :
   $graveyard = new \mementomei\Beloved($GLOBALS['db']);
   if (array_key_exists('id', $_REQUEST) && $_REQUEST['id'] != '') {      
      $graveyard->loadFromId($_REQUEST['id']);
      $graveyard->delete();
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
         $graveyard = new \mementomei\Beloved($GLOBALS['db']);
         $graveyard->loadFromId($_REQUEST['id']);
         $graveyard->setData($_REQUEST['value'], 'name');
         $graveyard->update();
         $graveyard->loadFromId($_REQUEST['id']);
         echo $graveyard->getData('name');
         exit;
       }
   break;
default:
   $this->getTemplate()->setBlock('header','administrator/header.phtml'); 
   $this->getTemplate()->setBlock('middle','administrator/graveyard/list.phtml');
   $this->getTemplate()->setBlock('footer','administrator/graveyard/footer.phtml');  
break;
}