<?php
namespace login\user;
/**
 * Description of UserColl
 *
 * @author caiofior
 */
class UserColl extends \ContentColl {
      public function __construct($db) {
         parent::__construct(new \login\user\User($db));
      }
    /**
      * Customizes select statement
      * @param Zend_Db_Select $select Zend Db Select
      * @param array $criteria Filtering criteria
      * @return Zend_Db_Select Select is expected
      */
    protected function customSelect( \Zend\Db\Sql\Select $select,array $criteria ) {
       $select->join('profile', 'profile.id=user.profile_id', array('profile.first_name'=>'first_name','profile.last_name'=>'last_name'), \Zend\Db\Sql\Select::JOIN_LEFT);
       if (array_key_exists('role_id', $criteria) && $criteria['role_id'] != '') {
          $select->where('`role_id` = '.intval($criteria['role_id']));
       }
       return $select;
    }
}
