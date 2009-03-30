<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUser extends BaseUser
{
  protected
    $profile        = null,
    $groups         = null,
    $permissions    = null,
    $allPermissions = null;

  public function getGravatarUrl($size = 40)
  {
    $default = "http://www.somewhere.com/homestar.jpg";

    $url = 'http://www.gravatar.com/avatar.php?gravatar_id='.md5(strtolower($this->email_address)).'&default='.urlencode($default).'&size='.$size;
    return $url;
  }

  public function getName()
  {
    return trim($this->getFirstName().' '.$this->getLastName());
  }

  public function __toString()
  {
    return $this->getName();
  }

  public function setPassword($password)
  {
    if (!$password && 0 == strlen($password))
    {
      return;
    }

    if (!$salt = $this->getSalt())
    {
      $salt = md5(rand(100000, 999999).$this->getUsername());
      $this->setSalt($salt);
    }
    $modified = $this->getModified();
    if ((!$algorithm = $this->getAlgorithm()) || (isset($modified['algorithm']) && $modified['algorithm'] == $this->getTable()->getDefaultValueOf('algorithm')))
    {
      $algorithm = sfSympalConfig::get('sfSympalUserPlugin', 'signin_algorithm_callable', 'sha1');
    }
    $algorithmAsStr = is_array($algorithm) ? $algorithm[0].'::'.$algorithm[1] : $algorithm;
    if (!is_callable($algorithm))
    {
      throw new sfException(sprintf('The algorithm callable "%s" is not callable.', $algorithmAsStr));
    }
    $this->setAlgorithm($algorithmAsStr);

    parent::_set('password', call_user_func_array($algorithm, array($salt.$password)));
  }

  public function setPasswordBis($password)
  {
  }

  public function checkPassword($password)
  {
    if ($callable = sfSympalConfig::get('sfSympalUserPlugin', 'signin_algorithm_callable'))
    {
      return call_user_func_array($callable, array($this->getUsername(), $password, $this));
    }
    else
    {
      return $this->checkPasswordByGuard($password);
    }
  }

  public function checkPasswordByGuard($password)
  {
    $algorithm = $this->getAlgorithm();
    if (false !== $pos = strpos($algorithm, '::'))
    {
      $algorithm = array(substr($algorithm, 0, $pos), substr($algorithm, $pos + 2));
    }
    if (!is_callable($algorithm))
    {
      throw new sfException(sprintf('The algorithm callable "%s" is not callable.', $algorithm));
    }

    return $this->getPassword() == call_user_func_array($algorithm, array($this->getSalt().$password));
  }

  public function addGroupByName($name, $con = null)
  {
    $group = Doctrine::getTable('Group')->findOneByName($name);
    if (!$group)
    {
      throw new Exception(sprintf('The group "%s" does not exist.', $name));
    }

    $ug = new UserGroup();
    $ug->setUser($this);
    $ug->setGroup($group);

    $ug->save($con);
  }

  public function addPermissionByName($name, $con = null)
  {
    $permission = Doctrine::getTable('Permission')->findOneByName($name);
    if (!$permission)
    {
      throw new Exception(sprintf('The permission "%s" does not exist.', $name));
    }

    $up = new UserPermission();
    $up->setUser($this);
    $up->setPermission($permission);

    $up->save($con);
  }

  public function hasGroup($name)
  {
    $this->loadGroupsAndPermissions();
    return isset($this->groups[$name]);
  }

  public function getGroupNames()
  {
    $this->loadGroupsAndPermissions();
    return array_keys($this->groups);
  }

  public function hasPermission($name)
  {
    $this->loadGroupsAndPermissions();
    return isset($this->allPermissions[$name]);
  }

  public function getPermissionNames()
  {
    $this->loadGroupsAndPermissions();
    return array_keys($this->allPermissions);
  }

  // merge of permission in a group + permissions
  public function getAllPermissions()
  {
    if (!$this->allPermissions)
    {
      $this->allPermissions = array();
      $permissions = $this->getPermissions();
      foreach ($permissions as $permission)
      {
        $this->allPermissions[$permission->getName()] = $permission;
      }

      foreach ($this->getGroups() as $group)
      {
        foreach ($group->getPermissions() as $permission)
        {
          $this->allPermissions[$permission->getName()] = $permission;
        }
      }
    }

    return $this->allPermissions;
  }

  public function getAllPermissionNames()
  {
    return array_keys($this->getAllPermissions());
  }

  public function loadGroupsAndPermissions()
  {
    $this->getAllPermissions();
    if (!$this->permissions)
    {
      $permissions = $this->getPermissions();
      foreach ($permissions as $permission)
      {
        $this->permissions[$permission->getName()] = $permission;
      }
    }
    if (!$this->groups)
    {
      $groups = $this->getGroups();
      foreach ($groups as $group)
      {
        $this->groups[$group->getName()] = $group;
      }
    }
  }
  public function reloadGroupsAndPermissions()
  {
    $this->groups         = null;
    $this->permissions    = null;
    $this->allPermissions = null;
  }

  public function delete(Doctrine_Connection $conn = null)
  {
    // delete profile if available
    try
    {
      if ($profile = $this->getProfile())
      {
        $profile->delete();
      }
    }
    catch (Exception $e)
    {
    }

    return parent::delete($conn);
  }

  public function setPasswordHash($v)
  {
    if (!is_null($v) && !is_string($v))
    {
      $v = (string) $v;
    }

    if ($this->password !== $v)
    {
      $this->password = $v;
    }
  }
}