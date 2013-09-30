<?php
session_start();
require_once 'classes/database.class.php';
require_once 'functions.php';

if (isset($_POST['email'])) {
	$email=$_POST['email'];
	$password=$_POST['password'];

	$clean_email = strip_tags(stripslashes($email));
	$clean_password = sha1(strip_tags(stripslashes($password)));

	$db = new database();
	$db->query('SELECT * FROM players WHERE email = :clean_email AND password = :clean_password');
	$db->bind(':clean_email', $clean_email);
	$db->bind(':clean_password', $clean_password);

	try {
		$rs = $db->single();
	} catch (Exception $e) {
		echo 'Exception on login: ' . $e->getMessage();
	}

	$numofrows = $db->rowCount();

	if($numofrows==1){
		$_SESSION['user'] = array(
			'name' => $rs['username'],
			'email' => $rs['email'],
			'access' => $rs['access'],
			'fname' => $rs['fname'],
			'lname' => $rs['lname']
		);
		$_SESSION['player_id'] = $rs['player_id'];
		header('location:index.php');
	}
	else {
		header("location:login.php?action=failed");
	}
	$error = 'logging in';
} elseif ($_GET['action'] == 'failed') {
	$error = 'Bad email or password';
} elseif ($_GET['action'] == 'logout') {
	unset($_SESSION['user']);
	unset($_SESSION['player_id']);
	header("location:index.php");
} else {
	$error = 'no login info sent';
}

?>

<html>
<head>
	<title>Datarunner</title>
	<link href="css/themes/pepper-grinder/jquery-ui.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
 	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
</head>

<body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php">Datarunner</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="about.php">About</a></li>
              <li><a href="contact.php">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Play <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="organize.php">Start a Tourney</a></li>
                  <li><a href="register-tourney.php">Register for a Tourney</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Your Account</li>
                  <li><a href="decks.php">Manage Decks</a></li>
                  <li><a href="stats.php">View Stats</a></li>
                  <li><a href="profile.php">Manage Profile</a></li>
                </ul>
              </li>
            </ul> 
            <form class="navbar-form pull-right" action="login.php" method="post">
              <input class="span2" type="text" placeholder="Email" name="email">
              <input class="span2" type="password" placeholder="Password" name="password">
              <button type="submit" class="btn">Sign in</button>
            </form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <div class="hero-unit">
		<?php echo $error; ?>
	  </div>

<?php
require_once 'footer.php';
?>
