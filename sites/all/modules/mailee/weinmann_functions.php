<?php

function weinmann_parse_doctor($doctor){
    $name = _weinmann_format_doctor_name($doctor->title);
    $emails = _weinmann_parse_doctor_emails($doctor->field_email);
    $ret = aa('name',$name,'email',implode(',',$emails));
    return $ret;
}

function weinmann_get_recipients(){
    global $node;
    $my_node = node_load($node->nid);
    $patient_name = $my_node->field_nome_remetente[0]['value'];
    $patient_email = $my_node->field_email_remetente[0]['email'];
    $doctor = node_load($node->field_medico_destinatario[0]['nid']);
    if(_weinmann_doctor_in_list($doctor)):
       $doctor = weinmann_parse_doctor($doctor);
       $ret['names']['doctor'] = $doctor['name'];
       $ret['names']['patient'] = $patient_name;
       $ret['emails']['doctor'] = $doctor['email'];
       $ret['emails']['patient'] = $patient_email;
       $ret['emails']['sec_copy'] = 'suporte@mmdadigital.com.br';
       $ret['emails']['sec_copy2'] = 'contato@mendizabal.com.br';
    elseif(IN_TEST):
       $ret['names']['doctor'] = 'Dr. Foo Manchu';
       $ret['names']['patient'] = 'Foo Barbaz';
       $ret['emails']['doctor'] = 'maiquel@mmdadigital.com.br';
       $ret['emails']['patient'] = 'daniel@mmdadigital.com.br';
    else:
       $ret['emails']['doctor'] = $ret['names']['doctor'] = 'NONE';
       $ret = false;
    endif;
    return $ret;
}

function _weinmann_doctor_in_list($doctor){
   return (strlen($doctor->field_email[0]['email'])) ? true : false;
}

function _weinmann_clear_doctor_name($word){
    if(preg_match('/\d+\s-\s/',$word)):
        $name = drupal_strtolower(preg_replace('/\d+\s-\s/','',$word));
    else:
        $name = drupal_strtolower($word);
    endif;
    return $name;
}

function _weinmann_parse_doctor_emails($emails){
    $ret = array();
    foreach($emails as $data)
       if(strlen($data['email'])) $ret[] = $data['email'];
    return $ret;
}

function _weinmann_format_doctor_name($word){
    $name = _weinmann_clear_doctor_name($word);
    if(strpos($name,' ')):
        $words_of_name = explode(' ',$name);
        foreach($words_of_name as $k => $word)
            $words_of_name[$k] = (strlen($word) > 2)? drupal_ucfirst($word) : $word;
        $name = implode(' ',$words_of_name);
    else:
        $name = drupal_ucfirst($name);
    endif;
    return $name;
}

