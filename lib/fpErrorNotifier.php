<?php

/**
 *
 * @package    fpErrorNotifier
 * 
 * @author     Maksim Kotlyar <mkotlar@ukr.net>
 */
class fpErrorNotifier
{
  /**
   * @var fpErrorNotifier
   */
  protected static $instance;
  
  /**
   * @var sfEventDispatcher
   */
  protected $dispather;
  
  /**
   * @var fpErrorNotifierHandler
   */
  protected $handler;
  
  /**
   * 
   * @param sfEventDispatcher $dispather
   * 
   * @return void
   */
  public function __construct(sfEventDispatcher $dispather)
  {
    $this->dispather = $dispather;
  }
  
  /**
   * 
   * @return sfEventDispatcher
   */
  public function dispather()
  {
    return $this->dispather;
  }
  
  /**
   * 
   * @param fpBaseErrorNotifierMessage
   * 
   * @return fpBaseErrorNotifierDecorator
   */
  public function decorator(fpBaseErrorNotifierMessage $message)
  {
    $options = sfConfig::get('sf_notify_decorator');
    $class = $options['class'];

    return new $class($message);  
  }

  /**
   * 
   * @return fpBaseErrorNotifierDriver
   */
  public function driver()
  {
    $options = sfConfig::get('sf_notify_driver');
    $class = $options['class'];
    
    return new $class($options['options']); 
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return fpBaseErrorNotifierMessage
   */
  public function message($title)
  {
    $options = sfConfig::get('sf_notify_message');
    $class = $options['class'];

    return new $class($title); 
  }
  
  /**
   * 
   * @param string $title
   * 
   * @return fpBaseErrorNotifierDecorator
   */
  public function decoratedMessage($title)
  {
    return $this->decorator($this->message($title));
  }
  
  /**
   * 
   * @return fpErrorNotifierHandler
   */
  public function handler()
  {
    if (empty($this->handler)) {
      $options = sfConfig::get('sf_notify_handler');
      $class = $options['class'];

      $this->handler = new $class($this->dispather(), $options['options']);
    }
    return $this->handler;
  }
  
  /**
   * 
   * @return fpErrorNotifierMessageHelper
   */
  public function helper()
  {
    $options = sfConfig::get('sf_notify_helper');
    $class = $options['class'];

    return new $class;
  }
  
  /**
   * 
   * @return sfContext|fpErrorNotifierNullObject
   */
  public function context()
  {
    if (!class_exists('sfContext') || !sfContext::hasInstance()) {
      return new fpErrorNotifierNullObject();
    }
    
    return sfContext::getInstance();
  }
  
  /**
   * 
   * @return fpErrorNotifier
   */
  public static function getInstance()
  {
    return self::$instance;
  }
  
  /**
   * 
   * @param fpErrorNotifier
   * 
   * @return fpErrorNotifier
   */
  public static function setInstance(fpErrorNotifier $notifier)
  {
    return self::$instance = $notifier;
  }
}