<?php if($is_front): ?>
    <div class="grid_3 mensagem">
<?php endif; ?>
     <?php if($title == 'Mensagem enviada com sucesso!'): ?>
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
        <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">
	        <div class="content">
	            <?php $medico = ucfirst(drupal_strtolower(preg_replace('/(\d+\s-\s)/','',$node->field_medico_destinatario[0]['safe']['title']))) ?>
	            <p class="destinatario"><span>para</span><?php echo $medico ?></p>
	            <p class="texto"><?php echo $node->title ?></p>
	            <p class="remetente"><span>de</span><?php echo $node->field_nome_remetente[0]['safe'] ?></p>
	        </div>
        </div>
        <?php if($preview): ?>
         <style type="text/css">
            #node-form .node-form div , #edit-preview{display:none}
            #edit-submit {background: url(<?php echo base_path(), drupal_get_path('theme','default') ?>/imagens/botoes.png) no-repeat -384px 0;border:none;height:42px;margin-bottom:10px;margin-left:0px;overflow:hidden;text-indent:-99999px;width:192px;}
            #node-form .voltar{background: url(<?php echo base_path(), drupal_get_path('theme','default') ?>/imagens/botoes.png) no-repeat 0 0!important;width:192px!important;}
            #node-form .voltar:hover{background-position:0 -42px!important;}
         </style>
        <?php endif ;?>
    <?php endif ;?>
<?php if($is_front): ?>
    </div>
<?php endif; ?>

