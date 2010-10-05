<?php
require 'autorun.php';

function pr($AorO){ echo '<pre>',print_r($AorO,1),'</pre>'; }
function a(){ return func_get_args(); }
function aa(){ $ks = $vs = array(); foreach(func_get_args() as $i => $v) if($i % 2) $vs[] = $v; else $ks[] = $v; if(count($ks) > count($vs)) array_push($vs,'');	return array_combine($ks,$vs); }

function sanitize($string){
	$patterns = array('/ã|á|à|â/i','/é|ê/i','/í/i','/ô|õ|ó/i','/ú|ü/i','/ç/i','/\s/');
	$replaces = array('a','e','i','o','u','c','-');
	return preg_replace($patterns,$replaces,$string);
}

function urlfy($string){
	return sanitize(strtolower($string));
}

class HRsmart {
    static $links = array(
        'base-uri'   => 'http://allis.ats.hrsmart.com/cgi-bin/a/',
        'targets' => array(
            'jobid'                     => 'highlight.cgi?jobid=',
            'alterar'                   => 'editprofile.cgi?action=edit',
            'buscar-vagas'              => 'searchjobs.cgi',
            'cadastro'                  => 'editprofile.cgi',
            'esqueci-senha'             => 'getpassword.cgi',
            'nao-sei-se-tenho-cadastro' => 'info.cgi?pagetoshow=contato'
        )
    );
    public static function validate_url_token(){
        return (self::isset_token() && (self::token_in_array() or self::url_have_job()));
    }
    public static function extract_url_jobid(){
        return preg_replace('/(jobid-)/','',$_GET['token']);
    }
    public static function token_in_array(){
        return in_array($_GET['token'],array_keys(self::$links['targets']));
    }
    public static function isset_token(){
        return isset($_GET['token']);
    }
    public static function url_have_job(){
        return strstr($_GET['token'],'jobid');
    }
}

class TestTokenValidate extends UnitTestCase {

    public function test_token_isset(){
       $this->assertTrue(HRsmart::isset_token());
    }

    public function test_token_in_array(){
        $this->assertTrue(HRsmart::token_in_array());
    }

    public function test_validate_token(){
        $this->assertTrue(HRsmart::isset_token());
    }

    public function test_url_have_job(){
        $this->assertTrue(HRsmart::url_have_job());
    }

}
/*
class TestUrlfyString extends UnitTestCase {
    public function test_sanitize(){
        $this->assertEqual('etica',sanitize('ética'));
    }

    public function test_urlfy(){
        $this->assertEqual('etica',urlfy('ética'));
    }

    public function test_sanitize_uppercase(){
        $this->assertEqual('etica',sanitize('Ética'));
        echo strtolower('Ética'),'<br />';
    }

    public function test_urlfy_uppercase(){
        $this->assertEqual('etica',urlfy('Ética'));
    }
}

/*
$FB = array();
$arr = a('',2,3,4,5,'','',1);
function array_element_is_valid($n){ return (is_numeric($n))? $n : false; }
class TestArrayFilter extends UnitTestCase{

    public function testEmptyItems(){
        global $arr,$FB;
        $FB['arr']= $arr;
        $filtered_arr = array_filter($arr,"array_element_is_valid");
        $this->assertIdentical($filtered_arr,a(2,3,4,5,1));
        $FB['filtered_arr'] = $filtered_arr;
    }
    public function __destruct(){
        global $FB;
        pr($FB);
    }
}
*/

/*
$roles = array('sul','sudeste','centro-oeste','nordeste','norte','equipe-prata','equipe-azul');

$feedback = array();
class TestIs extends UnitTestCase{

	public function testOnePermission(){
		global $feedback, $roles;
		$perms = array('sul');
		$feedback[__FUNCTION__] = $intersect = array_intersect($perms,$roles);

		$this->assertEqual('sul',implode(',',$intersect));
	}

	public function testMultiplePermissions(){
		global $feedback, $roles;
		$perms = array('sul','equipe-prata');
		$feedback[__FUNCTION__] = $intersect = array_intersect($perms,$roles);
		$this->assertEqual('sul,equipe-prata',join(',',$intersect));

	}

	public function testWithCountResults(){
		global $feedback,$roles;
		$perms = a('sul');
		$intersect = array_intersect($perms,$roles);
		$this->assertEqual(1,count($intersect));
		$perms2 = a('sul','equipe-prata');
		$intersect2 = array_intersect($perms2,$roles);
		$this->assertEqual(2,count($intersect2));
		$perms3 = array();
		$intersect3 = array_intersect($perms3,$roles);
		$feedback[__FUNCTION__] = $intersect3;
		$this->assertEqual(0,count($intersect3));
	}

	public function __destruct(){
		global $feedback,$arrays;
		pr($feedback);
	}
}*/
?>

