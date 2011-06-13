<?php

abstract class K_Field{
  
  protected $defaults = array();
  protected $name = 'field_name';
  protected $caption = 'field_caption';
  protected $value = '';
  protected $required = FALSE;
  protected $error = '';

  abstract function render();
  abstract function validate();

  function __construct($options = array()){
    $options = array_merge($this->defaults, $options);
    foreach ($options as $key => $value){
      $this->$key = $value;
    }
  }

  protected function _label(){
    $req = $this->required?' <em class="required">*</em>':'';
    echo '<label for="'.$this->name.'">'.htmlspecialchars($this->caption).'</label>'.$req.'<br />';   
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

class K_name extends K_Input{
  
  protected $defaults = array(
    'name' => 'name',
    'caption' => 'Name',
    'field_type' => 'text',
    'required' => TRUE,
  );

  public function validate(){
    if (!$this->required){
      return TRUE;
    }
    $valid = strlen($this->value) > 5;
    if (!$valid){
      $this->error = $lang('Error');
    }
    return $valid;
  }

}

class K_email extends K_Input{

  protected $defaults = array(
    'name' => 'email',
    'caption' => 'E-Mail',
    'field_type' => 'email',
    'required' => TRUE,
  );

  public function validate(){
    if (!$this->required){
      return TRUE;
    }
    $valid = filter_var($this->value, FILTER_VALIDATE_EMAIL); 
    if (!$valid){
      $this->error = $lang('Error');
    }
    return $valid;
  }

}

class K_message extends K_Field{
  
  protected $defaults = array(
    'name' => 'message',
    'caption' => 'Message',
    'required' => TRUE,
  );

  public function render(){
    echo '<li>';
    $this->_label();
    printf('<textarea name="%s" id="%s">%s</textarea>', $this->name, $this->name, $this->value);
    echo '</li>';
  }

  public function validate(){
    if (!$this->required){
      return TRUE;
    }
    $valid = strlen($this->value) > 5;
    if (!$valid){
      $this->error = $lang('Error');
    }
    return $valid;
  }

}

class Kontact{

  private $fields = array();
  private $url;
  private $data;

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

  private function validate(){
    $valid = TRUE;
    foreach($this->fields as $field){
      $fvalid = $field->validate();
      $valid = $valid?$fvalid:$valid;
    }
    return $valid;
  }

  public function addField($type, $options = array()){
    $class_name = 'K_'.$type;
    if (class_exists($class_name)){
      $this->fields[] = new $class_name($options);
      return TRUE;
    } else {
      echo '<p class="error">'.$this->lang('Unknown field type').'</p>';
      return FALSE;
    }
  }

  public function render(){
    if (isset($_POST['submit'])){
      $valid = $this->validate();
      if ($valid){
        $this->save($this->data);
      }
    }
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
