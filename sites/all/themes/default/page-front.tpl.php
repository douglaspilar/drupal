<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<?php
_pathauto_include();
$path = base_path() . drupal_get_path('theme','default');
$class = strtolower(pathauto_cleanstring($title));
?>
<head>
	<title><?php print $head_title; ?></title>
	<?php print $head; ?>
    <!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen" />
	<![endif]-->
	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie7.css" media="screen" />
	<![endif]-->
	<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="css/ie8.css" media="screen" />
	<![endif]-->
	<?php print $styles; ?>

	<?php
	//se ta na parte de administracao adiciona o css de admin na jogada
	if (arg(0) == 'admin' || ((arg(0) == 'node' && arg(1) == 'add') ||  (arg(0) == 'node' && arg(2) == 'edit')) ): ?>
		<style type="text/css" media="screen">
			@import url("<?php echo $path ?>/css/admin.css");
		</style>
	<?php endif ?>
	<script type="text/javascript"><?php /* Needed to avoid Flash of Unstyled Content in IE */ ?> </script>
</head>
<body class="<?php print $body_classes; echo ' page-' . $class ?>">

<div id="tudo">
	<div id="topo">
		<div class="container_12">
			<div class="logo grid_8">
			    <h1>
			        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">Mês do Médico Weinmann</a>
			    </h1>
			</div>
		    <div id="header" class="busca grid_4">
       			<?php echo $header ?>
		    </div>
		</div>
	</div>
	<div id="homenagem" class="container_12">
		<div class="grid_4 prefix_8 alpha omega">
		    <?php echo l('Envie sua homenagem','node/add/mensagem',aa('attributes',aa('class','envie-homenagem','title','Envie sua homenagem')))?>
		</div>
	</div>
	<div id='content'>
	    <div id="mensagens" class="container_12 clearfix">
	        <?php echo $content?>
		    <?php /*
		    <div class="grid_3 alpha mensagem">
			    <p class="destinatario"><span>para</span> Cícero Stein</p>
			    <p class="texto">Parabéns pra você, nesta data querida!</p>
			    <p class="remetente"><span>de</span> Ricardo Pinheiro</p>
		    </div>
		    <div class="grid_3 mensagem">
			    <p class="destinatario"><span>para</span> Cícero Stein</p>
			    <p class="texto">Parabéns pra você, nesta data querida!</p>
			    <p class="remetente"><span>de</span> Ricardo Pinheiro</p>
		    </div>
		    <div class="grid_3 mensagem">
			    <p class="destinatario"><span>para</span> Cícero Stein</p>
			    <p class="texto">Parabéns pra você, nesta data querida!</p>
			    <p class="remetente"><span>de</span> Ricardo Pinheiro</p>
		    </div>
		    <div class="grid_3 omega mensagem">
			    <p class="destinatario"><span>para</span> Cícero Stein</p>
			    <p class="texto">Parabéns pra você, nesta data querida!</p>
			    <p class="remetente"><span>de</span> Ricardo Pinheiro</p>
		    </div>
		    <div id="paginacao" class="grid_8 prefix_3">
			    <a href="#" title="Primeiro" class="pagina">Primeira</a>
			    <a href="#" title="Anterior" class="pagina">Anterior</a>
			    <a href="#" title="1" class="numero ativa">1</a>
			    <a href="#" title="2" class="numero">2</a>
			    <a href="#" title="3" class="numero">3</a>
			    <a href="#" title="4" class="numero">4</a>
			    <a href="#" title="5" class="numero">5</a>
			    <a href="#" title="Próxima" class="pagina">Próxima</a>
			    <a href="#" title="Última" class="pagina">Última</a>
		    </div>
		    */?>
	    </div>
    </div>
	<?php include 'inc/footer.php'; ?>
</div>
<?php /*<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> */?>
<?php print $scripts; ?>
<script src="js/jquery.pngFix.pack.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).pngFix();
    });
</script>
<?php print $closure; ?>
</body>
</html>

