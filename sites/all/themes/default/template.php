<?php
function default_preprocess_page(&$variables) {
	if ($variables['node']->type != "") {
		$variables['template_files'][] = "page-" . $variables['node']->type;
	}

	$alias = drupal_get_path_alias($_GET['q']);
	if ($alias != $_GET['q']) {
		$template_filename = 'page';
		foreach (explode('/', $alias) as $path_part) {
			$template_filename = $template_filename . '-' . $path_part;
			$variables['template_files'][] = $template_filename;
		}
	}
}

function default_preprocess_node(&$variables){
	if(drupal_is_front_page()) {
		$variables['template_files'][] = 'node-front';
	}

}

function default_node_form($form) {
  $output = "\n<div class=\"node-form\">\n";

  // Admin form fields and submit buttons must be rendered first, because
  // they need to go to the bottom of the form, and so should not be part of
  // the catch-all call to drupal_render().
  $admin = '';
  if (isset($form['author'])) {
    $admin .= "    <div class=\"authored\">\n";
    $admin .= drupal_render($form['author']);
    $admin .= "    </div>\n";
  }
  if (isset($form['options'])) {
    $admin .= "    <div class=\"options\">\n";
    $admin .= drupal_render($form['options']);
    $admin .= "    </div>\n";
  }
  $buttons = drupal_render($form['buttons']);

  // Everything else gets rendered here, and is displayed before the admin form
  // field and the submit buttons.
  //$output .= "<div class=\"cartao grid_8\">\n" ;
  $output .= "   <div class=\"formulario\">";
  $output .= "      <h2>Envie uma homenagem ao seu médico</h2>";
  $output .= "          <div class=\" standard\">\n";
              $output .= drupal_render($form);
  $output .= "          </div>\n";
  $output .= "   </div>\n";
  //$output .= "</div>\n";

  if (!empty($admin)) {
    $output .= "  <div class=\"admin\">\n";
    $output .= $admin;
    $output .= "  </div>\n";
  }
  $output .= $buttons;
  $output .= "</div>\n";

  return $output;
}

