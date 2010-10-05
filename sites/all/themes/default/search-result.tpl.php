<?php $node = $result['node'] ?>
<?php if($node->type != 'medico'):?>
    <div class="grid_3 alpha mensagem">
       <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">
            <div class="content">
                <?php $medico = explode(' - ',$node->field_medico_destinatario[0]['safe']['title']) ?>
                <p class="destinatario"><span>para</span><?php echo ucfirst($medico[1]) ?></p>
                <p class="texto"><?php echo $node->title ?></p>
                <p class="remetente"><span>de</span><?php echo ucfirst($node->field_nome_remetente[0]['safe']) ?></p>
            </div>
        </div>
    </div>
	<?php $total_count++ ?>
<?php endif ?>

