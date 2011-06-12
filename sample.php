<h1>Sample Kontact form</h1>
<?php

require 'Kontact.php';

$kontact = new Kontact();
$kontact->addField('name');
$kontact->render();

?>
