<?php
require 'autorun.php';

function pr($AorO){ echo '<pre>',print_r($AorO,1),'</pre>'; }

function a(){ return func_get_args(); }

function aa(){
	$ks = $vs = array();
	foreach(func_get_args() as $i => $v)
		if($i % 2) $vs[] = $v;else $ks[] = $v;
	if(count($ks) > count($vs)) array_push($vs,'');
	return array_combine($ks,$vs);
}

function addCliente(){
	$k = a('nome','sobrenome','email','telefone','cpf-cnpj','empresa','endereco');
	$v = func_get_args();
	while(count($k) > count($v))
		array_push($v,'');
	return array_combine($k,$v);
}


class Orcamento{
	private $_orc = array();

	public function __construct(){
		//cria o orcamento caso nao exista
		if(!isset($_SESSION['Orcamento']))
			$_SESSION['Orcamento'] = array();
		$this->_orc = $_SESSION['Orcamento'];
	}
		
	public function __set($var,$value){
		if(is_array($value) && array_key_exists('produto_nid',$value)):
			$this->_orc[$var][] = $value;
		else:
			$this->_orc[$var] = $value;
		endif;
	}
	
	public function __get($var){
		return $this->_orc[$var];
	}
	
	public function save(){
		$_SESSION['Orcamento'] = $this->_orc;
	}
	
	public function destroy(){
		$_SESSION['Orcamento'] = array();
	}
	
	public function add_item($produto_nid,$ref,$cor,$qtd){
		$this->itens = aa('produto_nid',$produto_nid,'ref',$ref,'cor',$cor,'qtd',$qtd);
		$this->save();
	}
	
	public function remove_item($index){
		$arr = $this->itens;
		unset($arr[$index]);
		$this->itens = $arr;
		$this->save();
	}
	
	public function get_itens(){
		$nid = $this->itens[0]->produto_id;
		//pr(node_load($nid,null,true));
	}
	
	public function get_total_selecionados(){
		$total = 0;
		foreach($this->itens as $item)
			$total += $item['qtd'];
		return $total;
	}
	
	public function add_cliente(){
		$k = a('nome','sobrenome','email','telefone','cpf-cnpj','empresa','endereco');
		$v = func_get_args();
		while(count($k) > count($v))
			array_push($v,'');
		$this->cliente = array_combine($k,$v);
		$this->save();
	}
	
	public static function is_complete(){
		return ($_SESSION['Orcamento']['complete'] === true);
	}
}
$orc = new Orcamento();
$cores = a('azul','amarelo','METAL','Preto');
$refs = a('F04', '101', '666', 'NERD');
$qtds = a(1,20,666,404);
$ids = a(23,404,666,0);

class TestOrcamento extends UnitTestCase{
	
	public function testNewOrcamento(){
		global $orc;
		$this->assertIdentical($_SESSION['Orcamento'],array());
	}
	
	public function testAddItem(){
		global $orc;
		$item[] = aa('produto_nid',1,'ref','F03','cor','Preto','qtd',1);
		$orc->add_item(1,'F03','Preto',1);
		$orc->status = 'ok';
		$this->assertIdentical($item,$_SESSION['Orcamento']['itens']);
	}
	
	public function testDestroy(){
		global $orc;
		$orc->destroy();
		$this->assertIdentical(array(),$_SESSION['Orcamento']);
	}
	
	public function testRemoveItem(){
		global $orc;
		$orc->remove_item(0);
		$this->assertEqual(0,count($_SESSION['Orcamentos']['itens']));
	}
	
	public function testAddMultiItens(){
		global $orc,$cores,$refs,$qtds,$ids;
		$items = array();
		foreach($ids as $k => $id):
			$items[] = aa('produto_nid',$id,'ref',$refs[$k],'cor',$cores[$k],'qtd',$qtds[$k]);
			$orc->add_item($id,$refs[$k],$cores[$k],$qtds[$k]);
		endforeach;
		$this->assertIdentical($items,$_SESSION['Orcamento']['itens']);
	}
	
	public function testTotalSelecionados(){
		global $qtds, $orc;
		$this->assertEqual(array_sum($qtds),$orc->get_total_selecionados());
	}
	
	public function testAddClient(){
		global $orc;
		$orc->add_cliente('Maiquel','Leonel','maiquel@mmdadigital.com.br','51 3464-1381','000.619.940-22','MMDADigital','Rua Germano Petersen Jr.,101/608');
		$cliente = addCliente('Maiquel','Leonel','maiquel@mmdadigital.com.br','51 3464-1381','000.619.940-22','MMDADigital','Rua Germano Petersen Jr.,101/608');
		$this->assertIdentical($cliente,$_SESSION['Orcamento']['cliente']);
	}
	public function testCallIsCompleteWithoutValue(){
		$this->assertIdentical(false,Orcamento::is_complete());
	}
	
	public function testIsCompleteWithValue(){
		global $orc;
		$orc->complete = true;
		$orc->save();
		$this->assertIdentical(true,Orcamento::is_complete());
	}
	public function __destruct(){
		pr($_SESSION);
		$_SESSION = array();
	}
}

