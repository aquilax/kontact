<?php

abstract class K_Field{
  
  protected $name = 'field_name';
  protected $label = 'field_label';
  protected $value = '';
  
  abstract function render();
  abstract function validate();

  protected function _label(){
    echo '<label for="'.$this->name.'">'.htmlspecialchars($this->label).'</label>';   
  }

}

abstract class K_Input extends K_Field{

  public function render(){
    echo '<li>';      
    $this->_label();
    printf('<input type="text" id="%s" name="%s" value="%s" />', $this->name, $this->name, $this->value);
    echo '</li>';
  }

}

class K_Name extends K_Input{
  
  public function validate(){
    return strlen($this->value) > 5;
  }

}

class K_Email extends K_Input{

  public function validate(){
    return filter_var($this->value, FILTER_VALIDATE_EMAIL); 
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

  public function addField($type){
    switch ($type){
      case 'name': 
        $this->fields[] = new K_Name();
        break;
      case 'email':
        $this->fields[] = new K_Email();
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
