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

    function find_user_by_email($email){
        $contact = pos($this->find('first',aa('email',$email)));
        return $contact->_data;
    }
}

class MaileeMessage extends MaileeConfig {
    public $element_name = 'messages';

    public function create_and_send($params) {
        $uri = $this->site . $this->element_name . '.xml';
        $p = array();
        foreach($params as $k => $v) $p[] = $k . '=' . rawurlencode($v);
        $Params = implode('&',$p);
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $new_message = drupal_http_request($uri,$headers,'POST',$Params);
        if(IN_TEST or $new_message->code >= 400):
            header('Content-Type: text/xml');
            echo $new_message->data;
            exit();
        endif;
        $message = new SimpleXMLElement($new_message->data);
        $ready_uri = $this->site . $this->element_name .'/'. $message->id . '/ready.xml';
        $res = drupal_http_request($ready_uri,$headers,'PUT','when=now');
        if(IN_TEST or $res->code >= 400):
           header('Content-Type: text/xml');
           echo $res->data;
           exit();
        else:
            return ($res)? true : false;
        endif;
    }
}

class MaileeList extends MaileeConfig {  }

