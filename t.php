<html>
<body>
<?php
  $formats = array (
      0=>'{ [0], "[1]" },',
      1=>'<option value="[0]">[1]</option>',
      2=>'option value="[0]"[1]</option>',
      3=>'<option value="[0]"[1]/option',
      4=>'<option value="[0]"[1]</option>',
      5=>'option value="[0]">[1]</option>',
      6=>'option value="[0]">[1]/option',
      7=>'last',
  );
  for ($i = 0; $i < count($formats); $i++) {
      echo $i."=".htmlspecialchars($formats[$i])."<br>";
  }
?>
</body>
</html>
