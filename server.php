 <?php
// Вывод заголовка с данными о кодировке страницы
header('Content-Type: text/html; charset=utf-8');
session_start(); 
// инициализируем переменные
$number = "";
$password  = "";
$surname = "";
$name = "";
$errors = array(); 
$email = "";
// связь с базой данных
$db = mysqli_connect('localhost', 'f0526567_111', '111', 'f0526567_111')or die('Connection error.');
mysqli_query($db, "SET NAMES 'utf8'");
// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  
  $name = mysqli_real_escape_string($db, $_POST['name']);
  $surname = mysqli_real_escape_string($db, $_POST['surname']);
  $number = mysqli_real_escape_string($db, $_POST['number']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  
	
  // отрабатываем ошибки (выводим предупреждение)
  if (empty($number)) { array_push($errors, "Number is required"); }
  if (empty($password)) { array_push($errors, "Password is required"); }
  if (empty($name)) { array_push($errors, "Name is required"); }
  if (empty($surname)) { array_push($errors, "Surname is required"); }
  if (empty($email)) { array_push($errors, "E-mail is required"); }

  $user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] == $email) {
      array_push($errors, "E-mail already exists");
    }
  }
  // Регистрируем пользователя, если нет ошибок
  if (count($errors) == 0) {
  	$password = md5($password);

  	$query = "INSERT INTO users (surname, name, phone_number, email, password) 
  			  VALUES('$surname', '$name', '$number', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['email'] = $email;
  	$_SESSION['msg'] = "You are now logged in";
  	header('location: login.html');
	exit();
  }
}
	// LOGIN USER
	if (isset($_POST['log_user'])) {
	$pass = "";
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	
  if (empty($email)) {array_push($errors, "E-mail is required");}
  if (empty($password)) {array_push($errors, "Password is required");}

  if (count($errors) == 0) {
  	$pass = md5($password);
  	$query = "SELECT * FROM users WHERE email='$email' AND password='$pass'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['email'] = $email;
  	  setcookie('name', '$email');
  	  
  	  $_SESSION['msg'] = "You are now logged in";
  	  header('location: account.html');
	  exit();
  	}
	else array_push($errors, "Wrong number/password combination");
  } 
}
//EXIT
if (isset($_POST['exit'])) {
    session_destroy();
    header('location: login.html');
	exit();
}
//
if (isset($_POST['lk1'])) {
    if(isset($_SESSION['email'])){
        header('location: account.html');
        //echo "YES";
        exit();
    }
    else {
        //echo "NO";
        header('location: login.html');
        exit();
    }
}
// LOGIN ADMIN
if (isset($_POST['log_admin'])) {
	$login = mysqli_real_escape_string($db, $_POST['login']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	if (empty($login)) {array_push($errors, "Login is required");}
	if (empty($password)) {array_push($errors, "Password is required");}
	
	if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['email'] = $email;
  	  $_SESSION['name'] = $name;
  	  $_SESSION['msg'] = "You are now logged in";
  	  header('location: main_admin.php');
	  exit();
  	}
	else array_push($errors, "Wrong number/password combination");
  } 
}
//ADD_DEBT
if (isset($_POST['add_debt'])) {
$number = "";
$november = "";
$october = "";
$now = "";

$number = mysqli_real_escape_string($db, $_POST['number']);
$november = mysqli_real_escape_string($db, $_POST['november']);
$october = mysqli_real_escape_string($db, $_POST['october']);
$now = mysqli_real_escape_string($db, $_POST['now']);
$query = "INSERT INTO p_debt (number, november, october) 
  			  VALUES('$number', '$november', '$october')";
  	mysqli_query($db, $query);
	header('location: add_debt.php');
	exit();
	$_SESSION['msg']="Успешно!";
	echo $_SESSION['msg'];
}
?>