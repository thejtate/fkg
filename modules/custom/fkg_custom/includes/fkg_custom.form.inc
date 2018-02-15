<?php
/**
 * @file
 * Custom page with site settings
 */

function fkg_custom_site_setings_form() {
  $form = array();

  $form['footer'] = array(
    '#type' => 'fieldset',
    '#title' => t('Bottom'),
    '#weight' => 1,
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );
  $form['footer']['copyright'] = array(
    '#type' => 'textfield',
    '#title' => t('Footer Copyright'),
    '#default_value' => variable_get('copyright', ''),
    '#size' => 80,
    '#maxlength' => 128,
  );


  return system_settings_form($form);
}