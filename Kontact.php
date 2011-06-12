<?php

abstract class K_Field{
  
  protected $defaults = array();
  protected $name = 'field_name';
  protected $caption = 'field_caption';
  protected $value = '';
  
  abstract function render();
  abstract function validate();

  function __construct($options = array()){
    $options = array_merge($this->defaults, $options);
    foreach ($options as $key => $value){
      $this->$key = $value;
    }
  }

  protected function _label(){
    echo '<label for="'.$this->name.'">'.htmlspecialchars($this->caption).'</label>';   
  }

}

abstract class K_Input extends K_Field{

  protected $field_type;

  public function render(){
    echo '<li>';      
    $this->_label();
    printf('<input type="%s" id="%s" name="%s" value="%s" />', $this->field_type, $this->name, $this->name, $this->value);
    echo '</li>';
  }

}

class K_Name extends K_Input{
  
  protected $defaults = array(
    'name' => 'name',
    'caption' => 'Name',
    'field_type' => 'text',
  );

  public function validate(){
    return strlen($this->value) > 5;
  }

}

class K_Email extends K_Input{

  protected $defaults = array(
    'name' => 'email',
    'caption' => 'E-Mail',
    'field_type' => 'email',
  );

  public function validate(){
    return filter_var($this->value, FILTER_VALIDATE_EMAIL); 
  }

}

class K_Message extends K_Field{
  
  protected $defaults = array(
    'name' => 'message',
    'caption' => 'Message',
  );

  public function render(){
    echo '<li>';
    $this->_label();
    printf('<textarea name="%s" id="%s">%s</textarea>', $this->name, $this->name, $this->value);
    echo '</li>';
  }

  public function validate(){
    return strlen($this->value) > 5;
  }

}

class Kontact{

  private $fields = array();
  private $url;
  
  public function __construct($url = FALSE){
    $this->url = $url?$url:$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
  }

  private function lang($string){
    return $string;
  }

  private function render_header(){
    printf ('<form method="post" action="%s">', $this->url);
  }
  
  private function render_fields(){
    echo '<ul>';
    foreach ($this->fields as $field){
      $field->render();
    }
    echo '</ul>';
  }

  private function render_buttons(){
    printf ('<input type="submit" name="submit" value="%s" />', $this->lang('Send'));
  }

  private function render_footer(){
    echo '</form>';
  }

  public function addField($type, $options = array()){
    switch ($type){
      case 'name': 
        $this->fields[] = new K_Name($options);
        break;
      case 'email':
        $this->fields[] = new K_Email($options);
        break;
      case 'message':
        $this->fields[] = new K_Message($options);
        break;
      default: echo '<p class="error">'.$this->lang('Unknown field type').'</p>';  
    }
  }

  public function render(){
    if (!$this->fields){
      echo '<p class="error">'.$this->lang('No fields defined').'</p>';
      return FALSE;
    }
    echo $this->render_header();
    echo $this->render_fields();
    echo $this->render_buttons();
    echo $this->render_footer();
  }
}

?>
