<html>
<body>
<h1>Sample Kontact form</h1>
<?php

require 'Kontact.php';

$kontact = new Kontact('bg');
$kontact->addField('name');
$kontact->addField('email');
$kontact->addField('message');
$kontact->render();

?>
</body>
</html>
