<?php
require 'ActiveResource.php';

class MaileeConfig extends ActiveResource {
    public $site = MAILEE_CONFIG_SITE;
    public $element_name = 'contacts';
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

    function find_user_by_email($email){
        $contact = pos($this->find('first',aa('email',$email)));
        return $contact->_data;
    }

    function update($data){
       $this->_data = $data;
       $uri = $this->site . $this->element_name . '/' . $this->_data['id'] . '.xml';
    }
}

class MaileeMessage extends MaileeConfig {
    public $element_name = 'messages';

    public function create_and_send($params) {
        $uri = $this->site . $this->element_name . '.xml';
        $p = array();
        foreach($params as $k => $v) $p[] = $k . '=' . rawurlencode($v);
        $Params = implode('&',$p);
        //pr($Params);
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $new_message = drupal_http_request($uri,array(),'POST',$Params);
        //pr($new_message);
        $message = new SimpleXMLElement($new_message->data);
        //pr($message);
        $ready_uri = $this->site . $this->element_name .'/'. $message->id . '/ready.xml';
        $res = drupal_http_request($ready_uri .'?when=now',array(),'PUT','when=now');
        return ($res)? true : false;
    }
}

class MaileeList extends MaileeConfig {  }
