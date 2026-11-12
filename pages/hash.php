<?php
echo "Пароль '123456' в виде хеша: <br><br>";
echo password_hash('123456', PASSWORD_DEFAULT);
?>