<?php if($is_front): ?>
    <div class="grid_3 mensagem">
<?php endif; ?>
     <?php if($title == 'Cartao enviado com sucesso!'): ?>
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
	            <?php $medico = explode(' - ',$node->field_medico_destinatario[0]['safe']['title']) ?>
	            <p class="destinatario"><span>para</span><?php echo $medico[1] ?></p>
	            <p class="texto"><?php echo $node->title ?></p>
	            <p class="remetente"><span>de</span><?php echo $node->field_nome_remetente[0]['safe'] ?></p>
	        </div>
        </div>
    <?php endif ;?>
<?php if($is_front): ?>
    </div>
<?php endif; ?>

