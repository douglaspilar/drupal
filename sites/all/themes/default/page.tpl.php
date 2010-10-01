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
	<?php print $styles; ?>

	<?php
	//se ta na parte de administracao adiciona o css de admin na jogada
	if (arg(0) == 'admin' || ((arg(0) == 'node' && arg(1) == 'add') ||  (arg(0) == 'node' && arg(2) == 'edit')) ): ?>
		<style type="text/css" media="screen">
			@import url("<?php echo $path ?>/css/admin.css");
		</style>
	<?php endif ?>
    <style type="text/css">

	</style>
	<script type="text/javascript"><?php /* Needed to avoid Flash of Unstyled Content in IE */ ?> </script>
</head>
<body class="<?php print $body_classes; echo ' page-' . $class ?>">

<div id="tudo">
	<div id="topo">
		<div class="container_12">
			<div class="logo grid_8"><h1><a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">Mês do Médico Weinmann</a></h1></div>
			<div id="header" class="busca grid_4">
                <?php echo $header ?>
			</div>
		</div>
	</div>

    <?php if(in_url('mensagem')):?>
    <div id="envelope" class="container_12 clearfix">
	    <div class="cartao prefix_2 grid_7">
	<?php else: ?>
	    <div id="formulario" class="container_12 clearfix">
	    <div class="grid_12">
    <?php endif; ?>
            <?php //pr(var_export(user_is_logged_in())) ?>
	        <?php if (!empty($tabs) && user_is_logged_in()): ?>
				<div class="tabs">
					<?php print $tabs; ?>
				</div>
			<?php endif; ?>
			<?php if((in_http_referer('node/add/mensagem') or in_http_referer('criar/mensagem')) && !user_is_logged_in()): ?>
                <div id="enviado" class="container_12">
                    <div class="enviado grid_12">
                    <h4>Cartão enviado com sucesso!</h4>
                    <div class="botoes-enviado">
                       <?php echo l('Enviar novo cartão','criar/mensagem',aa('attributes',aa('class','enviar-novo'))) ?>
                        <?php echo l('Voltar para a home','<front>', aa('attributes',aa('class','voltar'))) ?>
               		</div>
		            </div>
        	    </div>
            <?php else: ?>
    			<?php echo $content;?>
            <?php endif; ?>
	    </div>
	</div>
	<?php include 'inc/footer.php'; ?>
</div>
<?php /*<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> */?>
<?php print $scripts; ?>
<script src="js/jquery.pngFix.pack.js"></script>
<script src="js/ui.core.min.js"></script>
<script src="js/jquery.metadata.js"></script>
<script src="js/jquery.countdata.js"></script>
<?php /*<script type="text/javascript">
    $(document).ready(function(){
        $(document).pngFix();
    });
</script> */?>
<script type="text/javascript">
$(document).pngFix();
$('#edit-title').attr('maxlength',30);
$('#edit-title').addClass('countme {limit:20, stripHTMLTags:true, jqueryUI:true}');
$(".countme").countdata();
</script>
<?php print $closure; ?>
</body>
</html>

