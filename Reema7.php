<?php
$username = 'root';
$server = 'localhost';
$password = ' ';
$db = 'manag';

$conn = mysqli_connect($server,$username,$password,$db);


if (!$conn) {
    die("فشل الاتصال: " . mysqli_connect_error());
}


$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_BCRYPT); 
$gender = mysqli_real_escape_string($conn, $_POST['gender']);


$errors = [];

if (strlen($name) < 2) {
    $errors[] = 'يجب أن يكون الاسم على الأقل 2 أحرف';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'عنوان البريد الإلكتروني غير صحيح';
}

if (strlen($_POST['password']) < 8) {
    $errors[] = 'يجب أن تكون كلمة المرور على الأقل 8 أحرف';
}

if (empty($gender)) {
    $errors[] = 'يرجى تحديد الجنس';
}

// التحقق من وجود أخطاء
if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo '<div class="error">' . $error . '</div>';
    }
} else {
    
    $sql = "INSERT INTO users (name, email, password, gender) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password, $gender);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo 'تم التسجيل بنجاح!';
    } else {
        echo 'فشل التسجيل. يرجى المحاولة مرة أخرى.';
    }
}

mysqli_close($conn);
?>
