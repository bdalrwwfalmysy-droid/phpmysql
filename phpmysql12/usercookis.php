<?

function add( $name="",$password="",$age="",$level="")
{$data=$name." ".$password." ".$age." ".$level."\n";
$fil=fopen("table.text","w+");
fwrite($fil,$data);
}
?>
 <?php
session_start();

include "db.php";
?>
<?php
setcookie("user", $name = trim($_POST["name"]),time()+(68400*3),"/");
setcookie("password",$password = $_POST["password"],time()+(68400*3),"/");
print_r($_COOKIE);
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {

    $name = trim($_POST["name"]);
    // $username=$_POST["nameus"];
    // $id=$_POST["nu"];
    $password = $_POST["password"];
    $level = $_POST["level"];
    $age = $_POST["age"];


    if (empty($name) || empty($password)) {

        $message = "يرجى إدخال اسم المستخدم وكلمة المرور";
        echo  $message;

    } else
     {

        // تشفير كلمة المرور قبل تخزينها
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // تجهيز أمر الإضافة
        $sql = "select  id, name, age, password, userName from studentt;

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt,$name,$hashedPassword,$age,$level);

        if (mysqli_stmt_execute($stmt)) 
        {

            $message = "تم إنشاء الحساب بنجاح";
            add( $name,$password,$age,$level);
            echo  $message;
            

        }
         else {

            $message = "حدث خطأ، قد يكون اسم المستخدم موجوداً مسبقاً";
echo $message;
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ;?>">
  <label for="name">الاسم :</label>
        <input type="text" name="name" required>
<br>

<label for="nameus">اسم المستخدم:</label>
      <input type="text" name="nameus" required>
<label for="name">الرقم التسلسلي :</label>
      <input type="number" name="nu" required>
<br>
<label for="password">كلمة المرور:</label>
        <input type="password" name="password" required>
        <br>
        <label for="level">المستوئ :</label>

        <input type="number" name="level" required>
        <br>
        <label for="age">العمر:</label>

        <input type="number" name="age" main="18" max="60" required>
        <br>
<fieldset>
 <button type="submit">
            تسجيل الدخول
        </button>

</fieldset>
       
    </form>
</body>
</html>