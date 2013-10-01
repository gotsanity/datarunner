<?php
session_start();
require_once 'classes/database.class.php';
require_once 'functions.php';

// add code to detect form submission and add a new user
if (isset($_POST['password'])) {
	// validate user input filled out

	foreach ($_POST as $key => $value) {
		if (empty($_POST[$key])) {
			$missing[] = $key;
		}
	}

	// validate password match

	if ($_POST['password'] !== $_POST['verify_password']) {
		$error[] = 'Passwords do not match. Please go back and resubmit.';
	}


	// validate field lengths

	foreach ($_POST as $key => $value) {
		if (strlen($value) > 20) {
			if ($key == 'email') {
				if (strlen($value) > 40) {
					$error[] = $key.' is too long. Please make sure '.$key.' is under 40 characters in length';
				}
			} else {
				$error[] = $key.' is too long. Please make sure '.$key.' is under 20 characters in length';
			}
		}
	}

	// validate password length

	if (strlen($_POST['password']) <= 5) {
		$error[] = 'Password too short, please choose a password of 8 characters or more.';
	}

	if (strlen($_POST['password']) >= 21) {
		$error[] = 'Password too long, please choose a password of 20 characters or less.';
	}


	// validate email
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email'])){  
		// Return Error - Invalid Email 
		$error[] = 'Invalid email address, please choose another.'; 
	}

	if (isset($missing)) {
		$msg = 'User input missing, please fill out <strong>';
		$i = 1;
		foreach ($missing as $k => $v) {
			if (count($missing) == $i) {
				$msg = $msg . $v . ' ';
			} else {
				$msg = $msg . $v . ', ';
				$i++;
			}
		}
		$msg = $msg . '</strong>and resubmit form.';
		$error[] = $msg;
	}

	// check for errors and if none submit user
	if (!isset($error)) {
		
		$password = sha1($_POST['password']);

		$db = new database();
		$db->query('INSERT INTO players (fname, lname, email, username, password) VALUES (:fname, :lname, :email, :username, :password)');
		$db->bind(':fname', $_POST['fname']);
		$db->bind(':lname', $_POST['lname']);
		$db->bind(':email', $_POST['email']);
		$db->bind(':username', $_POST['username']);
		$db->bind(':password', $password);
		try {
			$db->execute();
			
			header('location:newuser.php?success=login');
		} catch (Exception $e) {
			die('Exception creating new user: ' . $e->getMessage());
		}
	}
}

require_once 'navbar.php';

// add code to display new user form and successfull submissions
?>
<div class="hero-unit">
	<div class="row">
		<div class="span4">
		<?php
		if (isset($error)) {
			foreach ($error as $key => $value) {
				print '<div class="alert alert-error">';
				print '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				echo $value;
				print '</div>';
			}
		}
		?>
		</div>
		<div class="span4">
			<?php if (!isset($_GET['success'])) { ?>
			<form name="newuser" method="post" action="newuser.php">
				<div class="input-append controls-row">
					<input type="text" name="fname" placeholder="John" class="tall-input" />
					<span class="add-on">First Name: </span>
				</div>

				<div class="input-append controls-row">
					<input type="text" name="lname" placeholder="Doe" class="tall-input" />
					<span class="add-on">Last Name: </span>
				</div>

				<div class="input-append controls-row">
					<input type="text" name="email" placeholder="example@email.com" class="tall-input" />
					<span class="add-on">Email: </span>
				</div>

				<div class="input-append">
					<input type="text" name="username" placeholder="Username" class="tall-input" />
					<span class="add-on">Username: </span>
				</div>

				<div class="input-append">
					<input type="password" name="password" placeholder="Password" class="tall-input" />
					<span class="add-on">Password: </span>
				</div>

				<div class="input-append">
					<input type="password" name="verify_password" placeholder="Verify Password" class="tall-input" />
					<span class="add-on">Verify Password: </span>
				</div>

					<input type="submit" class="btn btn-block btn-primary" value="Create Account" />
			</form>
			<?php } else { ?>
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Success</strong>, Please Log In:
				</div>
				<form name="login" action="login.php" method="post">
				<div class="input-append">
					<input type="email" name="email" placeholder="Email@domain.com" class="tall-input" />
					<span class="add-on">Email: </span>
				</div>

				<div class="input-append">
					<input type="password" name="password" placeholder="Password" class="tall-input" />
					<span class="add-on">Password: </span>
				</div>
				<div>
					<input type="submit" class="btn btn-primary" value="Sign In" />
				</div>
				</form>
			<?php } ?>
		</div>
		<div class="span4"></div>
	</div>
</div>

<?php
require_once 'footer.php';
?>
