<?php
/**
 * The template for displaying the breadcrumb
 *
 * @package Angilla
 * @since Angilla 1.0.0
 */
?>
<div class="angi-hot-crumble container page-breadcrumbs" role="navigation" <?php angi_fn_echo('element_attributes') ?>>
  <div class="row">
    <?php /* or do not use a model but a tc function (template tag) */ ?>
    <?php angi_fn_echo( 'breadcrumb' ) ?>
  </div>
</div>