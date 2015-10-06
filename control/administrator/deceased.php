<?php
if (array_key_exists('sEcho', $_REQUEST)) {
      $result = array();
      $deceasedColl = new \mementomei\DeceasedColl($GLOBALS['db']);
      $deceasedColl->loadAll($_REQUEST);
      $result['sEcho']=intval($_REQUEST['sEcho']);
      $request = $_REQUEST;
      unset($request['sSearch']);
      $result['iTotalRecords']=$deceasedColl->countAll($request);
      $result['iTotalDisplayRecords']=$deceasedColl->countAll($_REQUEST);
      $result['aaData']=array();
      $columns = $deceasedColl->getColumns();
      foreach($deceasedColl->getItems() as $key => $deceased) {
         $row=array();
         foreach($columns as $column) {
            $data = $deceased->getRawData($column);
            if ($column == 'actions') {
               $data = '<a class="actions modify" title="Modifica" href="?task=deceased&amp;action=edit&amp;id='.$deceased->getData('id').'">Modifica</a><a class="actions delete" title="Cancella" href="?task=deceased&amp;action=delete&amp;id='.$deceased->getData('id').'">Cancella</a>';
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
   $this->getTemplate()->setBlock('middle','administrator/deceased/edit.phtml');
   $this->getTemplate()->setBlock('footer','administrator/deceased/footer.phtml');  
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
            
      $deceased = new \mementomei\Deceased($GLOBALS['db']);
      if (array_key_exists('submit', $_REQUEST) && $this->formIsValid()) {
         $deceased->setData($_REQUEST);
         if (array_key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
            $deceased->update();
         } else {
            $deceased->insert();
         }
         header('Location: '.$GLOBALS['db']->config->baseUrl.'administrator.php?task=deceased');
         exit(); 
      }
   }
   break; 
case 'delete' :
   $deceased = new \mementomei\Deceased($GLOBALS['db']);
   if (array_key_exists('id', $_REQUEST) && $_REQUEST['id'] != '') {      
      $deceased->loadFromId($_REQUEST['id']);
      $deceased->delete();
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
         $deceased = new \mementomei\Deceased($GLOBALS['db']);
         $deceased->loadFromId($_REQUEST['id']);
         $deceased->setData($_REQUEST['value'], 'name');
         $deceased->update();
         $deceased->loadFromId($_REQUEST['id']);
         echo $deceased->getData('name');
         exit;
       }
   break;
default:
   $this->getTemplate()->setBlock('header','administrator/header.phtml'); 
   $this->getTemplate()->setBlock('middle','administrator/deceased/list.phtml');
   $this->getTemplate()->setBlock('footer','administrator/deceased/footer.phtml');  
break;
}