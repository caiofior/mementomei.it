<?php
if (array_key_exists('sEcho', $_REQUEST)) {
      $result = array();
      $parlourColl = new \mementomei\agency\ParlourColl($GLOBALS['db']);
      $parlourColl->loadAll($_REQUEST);
      $result['sEcho']=intval($_REQUEST['sEcho']);
      $request = $_REQUEST;
      unset($request['sSearch']);
      $result['iTotalRecords']=$parlourColl->countAll($request);
      $result['iTotalDisplayRecords']=$parlourColl->countAll($_REQUEST);
      $result['aaData']=array();
      $columns = $parlourColl->getColumns();
      foreach($parlourColl->getItems() as $key => $parlour) {
         $row=array();
         foreach($columns as $column) {
            $data = $parlour->getRawData($column);
            if ($column == 'actions') {
               $data = '<a class="actions modify" title="Modifica" href="?task=parlour&amp;action=edit&amp;id='.$parlour->getData('id').'">Modifica</a><a class="actions delete" title="Cancella" href="?task=beloved&amp;action=delete&amp;id='.$parlour->getData('id').'">Cancella</a>';
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
   $this->getTemplate()->setBlock('middle','administrator/parlour/edit.phtml');
   $this->getTemplate()->setBlock('footer','administrator/parlour/footer.phtml');  
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
      $parlour = new \mementomei\agency\Parlour($GLOBALS['db']);
      if (array_key_exists('submit', $_REQUEST) && $this->formIsValid()) {
         $parlour->setData($_REQUEST);
         if (array_key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
            $parlour->update();
         } else {
            $parlour->insert();
         }
         header('Location: '.$GLOBALS['db']->config->baseUrl.'administrator.php?task=parlour');
         exit(); 
      }
   }
   break; 
case 'delete' :
   $parlour = new \mementomei\agency\Parlour($GLOBALS['db']);
   if (array_key_exists('id', $_REQUEST) && $_REQUEST['id'] != '') {      
      $parlour->loadFromId($_REQUEST['id']);
      $parlour->delete();
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
         $parlour = new \mementomei\agency\Parlour($GLOBALS['db']);
         $parlour->loadFromId($_REQUEST['id']);
         $parlour->setData($_REQUEST['value'], 'name');
         $parlour->update();
         $parlour->loadFromId($_REQUEST['id']);
         echo $parlour->getData('name');
         exit;
       }
   break;
case 'cod_istat_n':
    if (!class_exists('comuni\Autoload')) {
        require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'comuni'.DIRECTORY_SEPARATOR.'Autoload.php';
        \comuni\Autoload::getInstance();
    }
    $comuniColl = new \comuni\ComuniColl($GLOBALS['db']);
    $comuniColl->loadAll(array('sSearch'=>$_REQUEST['term']));
    $result = array();
    foreach ($comuniColl->getItems() as $comuni) {
       $label =  $comuni->getData('denominazione_it');
       if ($comuni->getData('denominazione_de') != '') {
           $label .= ' - '.$comuni->getData('denominazione_de');
       }
       $label .= ' ('.$comuni->getRawData('provincia_sigla').')';
       $result[] = array(
           'label'=>$label,
           'value'=>$comuni->getData('cod_istat_n')
       );
    }
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 60*60*24));
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
    break;
case 'cap':
    if (!class_exists('trovacap\Autoload')) {
        require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'trovacap'.DIRECTORY_SEPARATOR.'Autoload.php';
        \trovacap\Autoload::getInstance();
    }
    $trovaCapColl = new \trovacap\TrovaCapColl($GLOBALS['db']);
    $trovaCapColl->loadAll(array('sSearch'=>$_REQUEST['city']));
    $result = array();
    foreach ($trovaCapColl->getItems() as $trovaCap) {
       $result[] = array(
           'value'=>$trovaCap->getData('capi_cap')
       );
    }
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 60*60*24));
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
    break;
default:
   $this->getTemplate()->setBlock('header','administrator/header.phtml'); 
   $this->getTemplate()->setBlock('middle','administrator/parlour/list.phtml');
   $this->getTemplate()->setBlock('footer','administrator/parlour/footer.phtml');  
break;
}