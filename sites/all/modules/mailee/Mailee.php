<?php
  require 'ActiveResource.php';

  class MaileeConfig extends ActiveResource {
    var $site = MAILEE_CONFIG_SITE;
    var $element_name = 'contacts';
  }

  class MaileeContact extends MaileeConfig{
    function find_by_internal_id($iid){
      $find = $this->find('first', array('internal_id' => $iid));
      return $find[0];
    }

    function unsubscribe($data=array()){
      #E.g. data --> {:reason => 'Trip to nowhere', :spam => false}
      //if(!$data['reason']) $data['reason'] = 'Motivo não especificado';
      //, array('unsubscribe' => $data)
      return $this->put('unsubscribe');
    }
  }

  class MaileeList extends MaileeConfig {

  }



?>

