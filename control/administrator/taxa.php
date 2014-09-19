<?php
if (key_exists('sEcho', $_REQUEST)) {
      $result = array();
      $taxaColl = new \flora\taxa\TaxaColl($GLOBALS['db']);
      $taxaColl->loadAll($_REQUEST);
      $result['sEcho']=intval($_REQUEST['sEcho']);
      $result['iTotalRecords']=$taxaColl->countAll();
      $result['iTotalDisplayRecords']=$taxaColl->count();
      $result['aaData']=array();
      $columns = $taxaColl->getColumns();
      foreach($taxaColl->getItems() as $key => $taxa) {
         $row=array();
         foreach($columns as $column) {
            $data = $taxa->getRawData($column);
            if ($column == 'actions') {
               $data = '<a class="actions modify" href="?task=taxa&amp;action=edit&amp;id='.$taxa->getData('id').'">Modifica</a><a class="actions delete" href="?task=taxa&amp;action=delete&amp;id='.$taxa->getData('id').'">Cancella</a>';
            } 
            $row[] = $data;     
         }
         $result['aaData'][]=$row;
      }
      header('Content-Type: application/json');
      echo json_encode($result);
      exit;
}
if (!key_exists('action',$_REQUEST)) {
   $_REQUEST['action']=null;
}
switch ($_REQUEST['action']) {
case 'edit':
   $this->getTemplate()->setBlock('middle','administrator/taxa/edit.phtml');
   $this->getTemplate()->setBlock('footer','administrator/taxa/footer.phtml');  
   if (
            key_exists('xhrValidate', $_REQUEST) ||
            key_exists('submit', $_REQUEST)
      ) {
      if (!key_exists('taxonomy_kind', $_REQUEST) ||$_REQUEST['taxonomy_kind']=='') {
          $this->addValidationMessage('initials','Il tipo di tassonomia è obbligatorio');
      }
      if (!key_exists('name', $_REQUEST) ||$_REQUEST['name']=='') {
          $this->addValidationMessage('name','Il nome è obbligatorio');
      }
      if (key_exists('submit', $_REQUEST) && $this->formIsValid()) {
         $taxa = new \flora\taxa\Taxa($GLOBALS['db']);
         if (key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
            $taxa->loadFromId($_REQUEST['id']);
         }
         $taxa->setData($_REQUEST);
         if (key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
            $taxa->update();
         } else {
            $taxa->insert();
         }
         $this->getTemplate()->setBlock('middle','administrator/taxa/list.phtml');
         $this->getTemplate()->setBlock('footer','administrator/taxa/footer.phtml'); 
      }
   }
   break; 
case 'delete' :
   $taxa = new \flora\taxa\Taxa($GLOBALS['db']);
   if (key_exists('id', $_REQUEST) && is_numeric($_REQUEST['id'])) {
      $taxa->loadFromId($_REQUEST['id']);
      $taxa->delete();
   }
   exit;
   break;
case 'taxakindlist':
   $taxaKindColl = new \flora\taxa\TaxaKindColl($GLOBALS['db']);
   $taxaKindColl->loadAll($_REQUEST);
   $result = array();
   foreach ($taxaKindColl->getItems() as $taxaKind) {
      $result[] = array(
          'label'=>$taxaKind->getData('name'),
          'value'=>$taxaKind->getData('id')
      );
   } 
   header('Content-Type: application/json');
   echo json_encode($result);
   exit;
   break;
default:
   $this->getTemplate()->setBlock('middle','administrator/taxa/list.phtml');
   $this->getTemplate()->setBlock('footer','administrator/taxa/footer.phtml');  
break;
}