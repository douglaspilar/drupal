<?php
require 'autorun.php';

function a(){ return func_get_args(); }
function aa(){
	$ks = $vs = array();
	foreach(func_get_args() as $i => $v)
		if($i % 2) $vs[] = $v;else $ks[] = $v;
	if(count($ks) > count($vs)) array_push($vs,'');
	return array_combine($ks,$vs);
}
function pr($AorO){ echo '<pre>',print_r($AorO,1),'</pre>'; }

$feedback = array();
$files = array();
$total = 2;
for($i = 0; $i < $total; $i++) $files[] = 'page-' . $i . '.jpg';

$limite_end;
$limite_max;
$limite_min;
$foto = getimagesize('/var/www/zaffari/sites/default/files/imagecache/pageflip_page/flipbook/book_1274731310/pages/page-651_1.jpg');
$feedback['testCalculate']['imagedetails'] = $foto;
$detalhes = array('width' => $foto[0], 'height' => $foto[1]);

class PageflipTest extends UnitTestCase{
	
	public function testRenameImages(){
		global $feedback,$files;
		foreach($files as $i => $file):
			for($j= 0; $j < 2;$j++):
				$new_file = preg_replace('/(\.jpg)/',"_$j.jpg",$file);
				$this->assertEqual($new_file,'page-'. $i . '_' . $j . '.jpg');
				$feedback['testRenameImages'][] = $new_file;
			endfor;
		endforeach;
	}
	
	public function testIndexesLimits(){
		global $files, $feedback, $limite_end, $limite_max, $limite_min, $total;
		$limite_end = count($files);
		$limite_max = (2 * $limite_end);
		$limite_min = 0;
		
		$this->assertEqual($total,$limite_end);
		
		$this->assertEqual((2 * $total),$limite_max);
		
		$feedback['testIndexesLimits'][] = array('limite_end' => $limite_end, 'limite_max' => $limite_max);
	}
	
	public function testCalcs(){
		global $files, $feedback, $limite_max, $limite_min, $limite_end;
		foreach($files as $file):
			for($j = 0; $j < 2; $j++):
				if($j):
					$feedback['testCalcs']['limite_min'][] = $limite_min++;
				else:
					$feedback['testCalcs']['limite_max'][] = --$limite_max;
				endif;
			endfor;
			if($limite_max == $limite_end):
				$this->assertEqual($limite_max,$limite_end);
				break;
			endif;
		endforeach;
		
	}
	
	public function testCalculate(){
		global $feedback, $detalhes;
		extract($detalhes);
		$largura_crop = (($width - 84) / 2);
		$altura_crop = ($height - 80);
		$offset_lado_direito = $largura_crop + 42;
		$feedback['testCalculate']['calculate'] = array( 'height' => $altura_crop, 
																	'width' => $largura_crop, 
																 'xoffset' => $offset_lado_direito);	
		$this->assertEqual(780,$largura_crop);
		$this->assertEqual(880,$altura_crop);
		$this->assertEqual(824,$offset_lado_direito);
	}
	
	
	public function __destruct(){
		global $feedback;
		pr($feedback);
	}
}
?>