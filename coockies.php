<?php
setcookie("user","abdualRaouf",time()+(68400*3),"/");
setcookie("password","0000",time()+(68400*3),"/");
print_r($_COOKIE);
echo($_COOKIE['user']);
?>