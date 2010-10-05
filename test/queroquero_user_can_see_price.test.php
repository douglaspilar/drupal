<?php
require 'autorun.php';
function pr($a){ echo '<pre>',print_r($a,1),'</pre>'; }

$feedback = array();
$roles = array(1=> 'anonymous user',2 =>'authenticated user',3 =>'usuário lista de noivas',
			5 => 'usuário newsletter',
			6 => 'admin Quero-Quero', 7=> 'lojas Quero-Quero',8 =>'Temporario');
$user1;
$user2;
class TestUser extends UnitTestCase{
	
	public function test_set_user_role_can_see(){
		global $roles, $feedback,$user1;
		$user1 = new StdClass();
		$index = rand(6,7);
		$user1->roles = array($index=>$roles[$index]);
		$feedback['test_set_user_can'] = $user1;
	}
	
	public function test_set_user_role_cant_see(){
		global $roles, $feedback,$user2;
		$user2 = new StdClass();
		$index = rand(1,8);
		while(in_array($index,array(6,7))) $index = rand(1,8);
		$user2->roles = array($index => $roles[$index]);
		$feedback['test_set_user_cant'] = $user2;
	}
	
	public function test_can_see_price(){
		global $roles, $feedback,$user1,$user2;
		$can_see = array(6,7);
		$user_role_can = array_keys($user1->roles);
		$user_role_cant = array_keys($user2->roles);
		$this->assertTrue(self::user_can_see_price($user1));
		$this->assertFalse(self::user_can_see_price($user2));
	}
	
	public function user_can_see_price($user){
		foreach(array_keys($user->roles) as $role):
			$can_see = (in_array($role,array(6,7)))? true : false;
		endforeach;
		return $can_see;
	}
	
	
	public function __destruct(){
		global $feedback;
		pr($feedback);
	}
}
?>
	