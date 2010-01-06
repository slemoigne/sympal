<?php

class sfSympalForm extends sfSympalExtendClass
{
  public function getRequiredFields(sfValidatorSchema $validatorSchema = null, $format = null)
  {
    if ($validatorSchema === null)
    {
      $validatorSchema = $this->getValidatorSchema();
    }
    if ($format === null)
    {
      $format = $this->getWidgetSchema()->getNameFormat();
    }
    $fields = array();

    foreach ($validatorSchema->getFields() as $name => $validator)
    {
      $field = sprintf($format, $name);
      if ($validator instanceof sfValidatorSchema)
      {
        // recur
        $fields = array_merge(
          $fields,
          $this->getRequiredFields($validator, $field.'[%s]')
        );
      }
      else if ($validator->getOption('required'))
      {
        // this field is required
        $fields[] = $field;
      }
    }

    return $fields;
  }

  public function sympalConfigure()
  {
    $form = $this->getSubject();
    if ($form instanceof sfFormDoctrine)
    {
      sfSympalFormToolkit::embedI18n($form->getObject(), $form);

      if (sfSympalConfig::get('remove_timestampable_from_forms', null, true))
      {
        unset($form['created_at'], $form['updated_at']);
      }
    }
    $widgetSchema = $form->getWidgetSchema();
    $requiredFields = $form->getRequiredFields();
    $widgetSchema->addOption('required_fields', $requiredFields);
    $widgetSchema->addFormFormatter('table', new sfSympalWidgetFormSchemaFormatterTable($widgetSchema));

    if ($form->hasRecaptcha())
    {
      sfSympalFormToolkit::embedRecaptcha($form);
    }
  }

  public function hasRecaptcha()
  {
    $forms = (array) sfSympalConfig::get('recaptcha_forms');
    return in_array(get_class($this->getSubject()), $forms) ? true : false;
  }

  public static function listenToFormPostConfigure(sfEvent $event)
  {
    $event->getSubject()->sympalConfigure();
  }

  public static function listenToFormFilterValues(sfEvent $event, $values)
  {
    $form = $event->getSubject();
    if ($form->hasRecaptcha())
    {
      $request = sfContext::getInstance()->getRequest();
      $captcha = array(
        'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
        'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
      );
      $values = array_merge($values, array('captcha' => $captcha));
    }

    return $values;
  }
}