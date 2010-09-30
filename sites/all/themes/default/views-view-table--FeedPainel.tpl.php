<?php header('content-type: text/xml'); ?>
<frases>
<?php foreach($rows as $row): ?>
  <frase>
    <id><?php echo $row['nid'] ?></id>
    <texto><?php echo $row['title'] ?></texto>
    <medico><?php echo preg_replace('/(\d+\s-\s)/','',$row['field_medico_destinatario_nid']) ?></medico>
    <paciente><?php echo $row['field_nome_remetente_value'] ?></paciente>
  </frase>
  <?php endforeach; ?>
</frases>

