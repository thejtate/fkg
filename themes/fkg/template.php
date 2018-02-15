<?php

/**
 * @file
 * Base theme functionality.
 */
define('BLOG_NID', 68);
define('LISTEN_LIVE_BLOCK_ID', 84);
define('HELPFUL_LINKS_BLOCK_ID', 85);
define('FKG_BLOG_MAIN_PAGE_URL', 'content/blog');

/**
 * Implements hook_preprocess_html()
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 */
function fkg_preprocess_html(&$vars) {
  $node = menu_get_object();

  $viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no',
    ),
  );
  $handheldhriendly = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'HandheldFriendly',
      'content' => 'false',
    ),
  );

  // Setup IE meta tag to force IE rendering mode.
  $meta_ie_render_engine = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'X-UA-Compatible',
      'content' => 'IE=edge',
    ),
    '#weight' => '-99999',
    '#prefix' => '<!--[if IE]>',
    '#suffix' => '<![endif]-->',
  );

  drupal_add_html_head($meta_ie_render_engine, 'meta_ie_render_engine');
  drupal_add_html_head($viewport, 'viewport');
  drupal_add_html_head($handheldhriendly, 'handheldhriendly');
  if ($node = menu_get_object()) {
    $vars['classes_array'][] = 'page';
    $vars['classes_array'][] = 'page-' . $node->type;
    if ($node->nid == BLOG_NID) {
      $vars['classes_array'][] = 'page-blog';
    }
    switch ($node->type) {
      case 'contact':
        $vars['classes_array'][] = 'page-contacts';
        break;
      case 'easy_blog_post':
        $vars['classes_array'][] = 'page-blog';
        break;
    }
  }
}

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function fkg_preprocess_page(&$vars, $hook) {
  $vars['footer_copyright'] = variable_get('copyright', '');
  $footer_tree = menu_tree_all_data('menu-footer-menu');
  $vars['footer_tree'] = menu_tree_output($footer_tree);
  if (isset($vars['node'])) {
    $node = $vars['node'];
    switch ($node->type) {
      case 'contact':
        break;
    }
  }
  if (drupal_is_front_page() && isset($vars['page']['content']['nodeblock_' . LISTEN_LIVE_BLOCK_ID])
    && isset($vars['page']['content']['nodeblock_' . HELPFUL_LINKS_BLOCK_ID])
  ) {
    $listen_live_nbid = 'nodeblock_' . LISTEN_LIVE_BLOCK_ID;
    $helpful_links_nbid = 'nodeblock_' . HELPFUL_LINKS_BLOCK_ID;

    $block['helpful_links'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('desc'),
      ),
      '#prefix' => '<section class="section section-bottom"><div class="site-container">',
      '#suffix' => '</div></section>',
    );

    $block['helpful_links']['listen_live'] = $vars['page']['content'][$listen_live_nbid];
    $block['helpful_links']['helpful_links'] = $vars['page']['content'][$helpful_links_nbid];

    unset($vars['page']['content'][$helpful_links_nbid]);

    $vars['page']['content'][$listen_live_nbid] = $block['helpful_links'];
  }
}

/**
 * Implements hook_preprocess_block()
 */
function fkg_preprocess_block(&$vars) {
  switch ($vars['block']->delta) {
    case 'services-block':
      $vars['theme_hook_suggestions'][] = 'block__section';
      $vars['classes_array'][] = 'section-services';
      break;
    case 'team-block':
      $vars['theme_hook_suggestions'][] = 'block__section';
      $vars['classes_array'][] = 'section-teams';
      $vars['additional_content'] = '<div class="popup-items">' . views_embed_view('team', 'block_1') . '</div>';
      break;
    case LISTEN_LIVE_BLOCK_ID:
      $vars['classes_array'][] = 'listen';
      break;
    case HELPFUL_LINKS_BLOCK_ID:
      $vars['classes_array'][] = 'helpful';
      break;
  }
}

/**
 * Implements template_preprocess_node().
 */
function fkg_preprocess_node(&$vars) {
  $node = $vars['node'];
  if (!$vars['page']) {
    $vars['theme_hook_suggestions'][] = 'node__' . $vars['type'] . '__' . $vars['view_mode'];
  }
  switch ($node->type) {
    case 'easy_blog_post':
      if (isset($vars['view_mode']) && ($vars['view_mode'] == 'full')) {
        $vars['back_button'] = l(t('< Back to blog listing'), FKG_BLOG_MAIN_PAGE_URL, array('attributes' => array('class' => 'back-btn')));
        $block = module_invoke('easy_blog', 'block_view', 'easy_blog_archive');
        $vars['archive_block'] = isset($block['content']) ? $block['content'] : '';
      }
      break;
  }
}

/**
 * Implements template_preprocess_field().
 */
function fkg_preprocess_field(&$vars) {
  $element = $vars['element'];
  switch ($element['#field_name']) {
    case 'field_section_content':
      $vars['classes_array'][] = 'desc';
      break;
    case 'field_client_links':
      $vars['classes_array'][] = 'desc';
      break;
  }
}

function fkg_views_pre_render(&$view) {
  $view_name = isset($view->name) ? $view->name : '';
  $current_display = isset($view->current_display) ? $view->current_display : '';
  switch ($view_name) {
    case 'easy_blog':
      if ($current_display == 'page') {
        $block = module_invoke('easy_blog', 'block_view', 'easy_blog_archive');
        $view->attachment_after = isset($block['content']) ? $block['content'] : '';
      }
      break;
  }
}