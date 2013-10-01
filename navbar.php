<html>
<head>
	<title>Datarunner</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/datarunner.css" rel="stylesheet">
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
			<?php if (is_admin()) { ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="organize.php">Manage Users</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Site Config</li>
                  <li><a href="register-tourney.php">Add Cards to Datarunner</a></li>
                  <li><a href="decks.php">Manage Decks</a></li>
                  <li><a href="stats.php">Manage Stats</a></li>
                </ul>
              </li>
			<?php } ?>
            </ul>
			<?php if (!isset($_SESSION['user'])) { ?>
            <form class="navbar-form pull-right" action="login.php" method="post">
              <input class="span2" type="text" placeholder="Email" name="email">
              <input class="span2" type="password" placeholder="Password" name="password">
              <button type="submit" class="btn">Sign in</button>
              <a href="newuser.php" class="btn">Register</a>
            </form>
			<?php } else { ?>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['user']['fname'].' '.$_SESSION['user']['lname']; ?> <b class="caret"></b></a>
		                <ul class="dropdown-menu">
							<li class="nav-header">User Controls</li>
							<li><a href="profile.php">Edit Profile</a></li>
							<li><a href="login.php?action=logout">Logout</a></li>
						</ul>
					</li>
				</ul>
			<?php } ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
