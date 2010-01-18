<?php

/**
 * Get a sfSympalMenu instance for the given menu root
 *
 * @param string $name  The slug of the root menu item you wish to retrieve
 * @param bool $showChildren Whether or not it should show the children when rendering
 * @param string $class The menu class to return an instance of
 */
function get_sympal_menu($name, $showChildren = null, $class = null)
{
  return sfSympalMenuSiteManager::getMenu($name, $showChildren, $class);
}

/**
 * Get a menu split into 2 instances, a primary and submenu
 *
 * @param string $name  The slug of the root menu item you wish to retrieve
 * @param string $showChildren  Whether or not it should show the children when rendering
 * @param string $max The max menu items to include in the first menu
 * @param string $split Whether to return a 2nd menu item with the remaining menu items in it
 * @return mixed Either one sfSympalMenu instance of an array with 2 sfSympalMenu instances
 */
function get_sympal_split_menus($name, $showChildren = null, $max = null, $split = false)
{
  $menu = sfSympalMenuSiteManager::getMenu($name, $showChildren);
  if ($menu)
  {
    return sfSympalMenuSiteManager::split($menu, $max, $split);
  } else {
    return false;
  }
}

/**
 * Get the Sympal admin menu instances
 *
 * @return sfSympalMenuAdminMenu $menu
 */
function get_sympal_admin_menu()
{
  $sympalContext = sfSympalContext::getInstance();
  $siteTitle = $sympalContext->getSite()->getTitle();
  $menu = new sfSympalMenuAdminMenu('Sympal Admin');
  $menu->setCredentials(array('ViewAdminBar'));

  if ($sympalContext->isAdminModule())
  {
    $menu->addChild('Go to Site Frontend', '@homepage');
    $menu->addChild('My Dashboard', '@sympal_dashboard');
  } else {
    $menu->addChild('Go to Site Admin', '@sympal_admin');
  }

  if (sfSympalConfig::get('page_cache', 'enabled'))
  {
    $menu->addChild('Clear Cache', '@sympal_clear_cache');
  }

  $menu->addChild('Content', null, array('label' => $siteTitle.' Content'));
  $menu->addChild('Site Administration', null, array('label' => $siteTitle.' Setup'));
  $menu->addChild('Security', null, array('label' => 'Users & Security'));
  $menu->addChild('Administration', null, array('label' => 'Global Setup'));

  sfApplicationConfiguration::getActive()->getEventDispatcher()->notify(new sfEvent($menu, 'sympal.load_admin_menu'));

  return get_partial('sympal_admin/menu', array('menu' => $menu));
}
