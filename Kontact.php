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

class K_Name extends K_Field{

  public function render(){
    echo '<li>';      
    $this->_label();
    printf('<input type="text" id="%s" name="%s" value="%s" />', $this->name, $this->name, $this->value);
    echo '</li>';
  }
  public function validate(){
    return TRUE;
  }

}

class Kontact{

  private $fields = array();
  
  private function _header(){
    echo 'header';
  }

  private function _buttons(){
    echo 'buttons';
  }

  private function _footer(){
    echo 'footer';
  }

  public function addField($type){
    switch ($type){
      case 'name': 
        $this->fields[] = new K_Name();
        break;
      default: echo '<p class="error">'.$this->lang('Unknown field type').'</p>';  
    }
  }

  public function render(){
    if (!$this->fields){
      echo '<p class="error">'.$this->lang('No fields defined').'</p>';
      return FALSE;
    }
    echo $this->_header();
    foreach ($this->fields as $field){
      $field->render();
    }
    echo $this->_buttons();
    echo $this->_footer();
  }
}

?>
