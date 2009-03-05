<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginLanguageTable extends Doctrine_Table
{
  public function getLanguageCodes()
  {
    $languages = $this->findAll();
    $languageKeys = array();
    foreach ($languages as $language)
    {
      $languageKeys[] = $language->getCode();
    }
    return $languageKeys;
  }
}