<?php
setcookie("user","abdualRaouf",time()+(68400*3),"/");
setcookie("password","0000",time()+(68400*3),"/");
print_r($_COOKIE);
echo($_COOKIE['user']);
?>


<?php

// ================================
// 1- الاتصال بـ MySQL
// ================================

$conn = mysqli_connect("localhost", "root", "");

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات");
}

// ================================
// 2- إنشاء قاعدة البيانات
// ================================

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS school_db");

mysqli_select_db($conn, "school_db");


// ================================
// 3- إنشاء جدول الطلاب
// ================================

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL
)
");


// ================================
// 4- إنشاء جدول المقررات
// ================================

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL
)
");


// ================================
// 5- إنشاء جدول الدرجات
// ================================

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    grade DECIMAL(5,2),

    FOREIGN KEY (student_id)
    REFERENCES students(student_id),

    FOREIGN KEY (course_id)
    REFERENCES courses(course_id)
)
");


// ================================
// إضافة بيانات تجريبية
// ================================

// لن يتم إدخال البيانات إلا إذا كان جدول الطلاب فارغاً

$check = mysqli_query($conn, "SELECT * FROM students");

if (mysqli_num_rows($check) == 0) {

    // الطلاب
    mysqli_query($conn, "
        INSERT INTO students (student_name)
        VALUES
        ('أحمد'),
        ('محمد'),
        ('علي'),
        ('خالد'),
        ('عبدالرؤوف')
    ");

    // المقررات
    mysqli_query($conn, "
        INSERT INTO courses (course_name)
        VALUES
        ('PHP'),
        ('قواعد البيانات'),
        ('شبكات')
    ");

    // الدرجات
    mysqli_query($conn, "
        INSERT INTO grades (student_id, course_id, grade)
        VALUES
        (1, 1, 85),
        (2, 1, 45),
        (3, 2, 70),
        (4, 2, 35),
        (5, 3, 98)
    ");
}


// ================================
// الاستعلام الأساسي
// ================================

$sql = "
SELECT
    students.student_id,
    students.student_name,
    courses.course_name,
    grades.grade

FROM grades

INNER JOIN students
ON grades.student_id = students.student_id

INNER JOIN courses
ON grades.course_id = courses.course_id
";


// ================================
// معرفة اختيار المستخدم
// ================================

$title = "الطلاب";

$result = null;

if (isset($_POST["show"])) {

    // ----------------------------
    // تحديد الكل
    // ----------------------------

    if (isset($_POST["all"])) {

        $result = mysqli_query($conn, $sql);

        $title = "جميع الطلاب";
    }


    // ----------------------------
    // الراسبين
    // ----------------------------

    elseif (isset($_POST["failed"])) {

        $query = $sql . "
        WHERE grades.grade < 50
        ";

        $result = mysqli_query($conn, $query);

        $title = "الطلاب الراسبون";
    }


    // ----------------------------
    // الطالب الأول
    // ----------------------------

    elseif (isset($_POST["first"])) {

        $query = $sql . "
        ORDER BY grades.grade DESC
        LIMIT 1
        ";

        $result = mysqli_query($conn, $query);

        $title = "الطالب الأول";
    }

}

?>

<!DOCTYPE html>

<html lang="ar">

<head>

    <meta charset="UTF-8">

    <title>نشاط الطلاب والدرجات</title>

    <style>

        body {
            font-family: Tahoma;
            direction: rtl;
            background-color: #f2f2f2;
        }

        .container {
            width: 800px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
        }

        .options {
            text-align: center;
            margin: 20px;
        }

        label {
            margin: 15px;
            font-size: 18px;
        }

        button {
            padding: 10px 30px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .pass {
            color: green;
            font-weight: bold;
        }

        .fail {
            color: red;
            font-weight: bold;
        }

    </style>

</head>


<body>

<div class="container">

    <h2>نظام الطلاب والمقررات والدرجات</h2>


    <!-- ===================== -->
    <!-- الفورم -->
    <!-- ===================== -->

    <form method="POST">

        <div class="options">

            <label>
                <input type="checkbox" name="all">
                تحديد الكل
            </label>

            <label>
                <input type="checkbox" name="failed">
                الراسبين
            </label>

            <label>
                <input type="checkbox" name="first">
                الطالب الأول
            </label>

        </div>


        <div style="text-align:center">

            <button type="submit" name="show">
                عرض النتائج
            </button>

        </div>

    </form>


    <?php

    if ($result) {

        echo "<h3>$title</h3>";

        echo "

        <table>

        <tr>

            <th>رقم الطالب</th>

            <th>اسم الطالب</th>

            <th>المقرر</th>

            <th>الدرجة</th>

            <th>الحالة</th>

        </tr>

        ";


        while ($row = mysqli_fetch_assoc($result)) {

            if ($row["grade"] >= 50) {

                $status = "<span class='pass'>ناجح</span>";

            } else {

                $status = "<span class='fail'>راسب</span>";

            }


            echo "

            <tr>

                <td>
                    {$row["student_id"]}
                </td>

                <td>
                    {$row["student_name"]}
                </td>

                <td>
                    {$row["course_name"]}
                </td>

                <td>
                    {$row["grade"]}
                </td>

                <td>
                    $status
                </td>

            </tr>

            ";

        }


        echo "</table>";

    }

    ?>

</div>

</body>

</html>