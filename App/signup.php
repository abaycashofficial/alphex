<?php
require("global.php");
$tab = "Sign Up";

if ($_POST["submit"])
{
	$email = $_POST["email"];
	$password = $_POST["password"];
	$username = $_POST["username"];

	if (empty($email) || empty($password) || empty($username))
  {
		$alert = '<p class="text-danger">All fields must be filled out.</p>';
	}
  else
  {
    $sql = $db->prepare("SELECT email FROM users WHERE email=?");
    $sql->execute([$email]);
    if ($sql->rowCount() != 0)
    {
      $alert = '<p class="text-danger">Email is already registered.</p>';
    }
    else
    {
      $sql = $db->prepare("SELECT username FROM users WHERE username=?");
      $sql->execute([$username]);
      if ($sql->rowCount() != 0)
      {
        $alert = '<p class="text-danger">Username is unavailable.</p>';
      }
      else
      {
				$password = password_hash($password, PASSWORD_DEFAULT);

				$sql = $db->prepare("INSERT INTO users (email, password, username) VALUES (?, ?, ?)");
				$sql->execute([$email, $password, $username]);

		    $ip_address = $_SERVER["REMOTE_ADDR"];

				$sql = $db->prepare("INSERT INTO logins (username, ip_address) VALUES (?, ?)");
		    $sql->execute([$username, $ip_address]);

        $_SESSION["authenticated_user"] = $username;

        header("location: /");
      }
    }
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
		<title>Sign Up - Alphex</title>
    <?php include("templates/head.php"); ?>
  </head>
  <body>
    <?php include("templates/navbar.php"); ?>
    <div class="container">
      <br>
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-4">
          <h4 class="fancy">Sign Up</h4>
					<hr class="mt-0">
          <?=$alert?>
          <form action="" method="post">
            <fieldset class="form-group">
              <input name="email" value="<?=htmlspecialchars($_POST["email"])?>" placeholder="Email" type="email" class="form-control" autofocus>
            </fieldset>
            <fieldset class="form-group">
              <input name="password" placeholder="Password" type="password" class="form-control">
            </fieldset>
            <fieldset class="form-group">
              <input name="username" value="<?=htmlspecialchars($_POST["username"])?>" placeholder="Username" type="text" class="form-control">
            </fieldset>
            <fieldset class="form-group">
              <input name="submit" type="submit" value="Sign Up" class="btn btn-primary">
            </fieldset>
          </form>
        </div>
        <div class="col-sm-4">
          <h4 class="fancy">Already have an account?</h4>
					<hr class="mt-0">
          <a href="/signin" class="btn btn-primary" role="button">Sign In</a>
        </div>
      </div>

		</div>
    <?php include("templates/footer.php"); ?>
  </body>
</html>
