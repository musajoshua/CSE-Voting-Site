<?php
  function load($class){
    include($class .".php");
  }
  load('IOchander');
  
  $class = new IOhandler;
?>
<!doctype html>
<html>
  <head>
    <title>your title</title>
  </head>
  <body>
    // for get all user from database
    <?php 
      $query = $class->getAll('your_table_name');
      
      echo $query;
    ?>
    
    //for get single id 
    
    $query = $class->getBy_id(your_id, table);
    
    echo $query;
    
  </body>
</html>
