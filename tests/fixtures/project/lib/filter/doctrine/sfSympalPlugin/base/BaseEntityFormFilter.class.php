<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Entity filter form base class.
 *
 * @package    filters
 * @subpackage Entity *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseEntityFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'site_id'             => new sfWidgetFormDoctrineChoice(array('model' => 'Site', 'add_empty' => true)),
      'entity_type_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'EntityType', 'add_empty' => true)),
      'entity_template_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'EntityTemplate', 'add_empty' => true)),
      'master_menu_item_id' => new sfWidgetFormDoctrineChoice(array('model' => 'MenuItem', 'add_empty' => true)),
      'last_updated_by'     => new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'created_by'          => new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'locked_by'           => new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'is_published'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'date_published'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'custom_path'         => new sfWidgetFormFilterInput(),
      'layout'              => new sfWidgetFormFilterInput(),
      'slug'                => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'groups_list'         => new sfWidgetFormDoctrineChoiceMany(array('model' => 'sfGuardGroup')),
      'permissions_list'    => new sfWidgetFormDoctrineChoiceMany(array('model' => 'sfGuardPermission')),
    ));

    $this->setValidators(array(
      'site_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Site', 'column' => 'id')),
      'entity_type_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'EntityType', 'column' => 'id')),
      'entity_template_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'EntityTemplate', 'column' => 'id')),
      'master_menu_item_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'MenuItem', 'column' => 'id')),
      'last_updated_by'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'created_by'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'locked_by'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'is_published'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'date_published'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'custom_path'         => new sfValidatorPass(array('required' => false)),
      'layout'              => new sfValidatorPass(array('required' => false)),
      'slug'                => new sfValidatorPass(array('required' => false)),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'groups_list'         => new sfValidatorDoctrineChoiceMany(array('model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'    => new sfValidatorDoctrineChoiceMany(array('model' => 'sfGuardPermission', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('entity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addGroupsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.EntityGroup EntityGroup')
          ->andWhereIn('EntityGroup.group_id', $values);
  }

  public function addPermissionsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.EntityPermission EntityPermission')
          ->andWhereIn('EntityPermission.permission_id', $values);
  }

  public function getModelName()
  {
    return 'Entity';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'site_id'             => 'ForeignKey',
      'entity_type_id'      => 'ForeignKey',
      'entity_template_id'  => 'ForeignKey',
      'master_menu_item_id' => 'ForeignKey',
      'last_updated_by'     => 'ForeignKey',
      'created_by'          => 'ForeignKey',
      'locked_by'           => 'ForeignKey',
      'is_published'        => 'Boolean',
      'date_published'      => 'Date',
      'custom_path'         => 'Text',
      'layout'              => 'Text',
      'slug'                => 'Text',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'groups_list'         => 'ManyKey',
      'permissions_list'    => 'ManyKey',
    );
  }
}