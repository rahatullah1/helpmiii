<html lang="en">
<head>
	<meta charset="utf-8"/>

		
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.js"
	type="text/javascript"></script>
	
	<style type="text/css">
	@import url("http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.css");
	
	#feedControl {
	margin-top : -5px;
	margin-left:4px;
	margin-right:4px;
	width : 440px;
	height: 300px;
	font-size: 6px;
	color: #9CADD0;
	}
	</style>
	
	<script type="text/javascript">
	$(document).ready(function() {
    //feed to parse
    var feed = "http://feeds.feedburner.com/raymondcamdensblog?format=xml";
    
    $.ajax(feed, {
        accepts:{
            xml:"application/rss+xml"
        },
        dataType:"xml",
        success:function(data) {
            //Credit: http://stackoverflow.com/questions/10943544/how-to-parse-an-rss-feed-using-javascript

            $(data).find("item").each(function () { // or "item" or whatever suits your feed
                var el = $(this);
                console.log("------------------------");
                console.log("title      : " + el.find("title").text());
                console.log("link       : " + el.find("link").text());
                console.log("description: " + el.find("description").text());
            });
    

        }   
    });
    
});
	</script>

	<title>Welcum to the 'HelpMiii'</title>
	
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>	
	<![endif]-->
		
	<link rel="stylesheet" href="welcum.css" />
	
</head>

<body onLoad="load();">
	<header>
		<img class="logo" title="Logo" name="helpmiii logo" src="images/helpmiiilogo.png" align="left" alt="HelpMiii" />
		<h1 class="welcome" align="left"><strong><em>HelpMiii</em></strong></h1>
		<nav>
			<ul>
				<li><a href="about.html">About</a></li>
				<li><a href="contact.html">Contact Us</a></li>
				<li><a href="newsfeed.html">Newsfeed</a></li>
			</ul>
		</nav>
	</header>
    

	<div class="wrap">
        
<?php
  require_once('connectvars.php');


  session_start();

  $error_msg = "";


  if (!isset($_SESSION['hm_id'])) {
    if (isset($_POST['login'])) {

      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  
      $user_id_proof = mysqli_real_escape_string($dbc, trim($_POST['idproof']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_id_proof) && !empty($user_password)) {
        $query = "SELECT hm_id, id_proof FROM hm_profile WHERE id_proof = '$user_id_proof' AND password = SHA('$user_password')";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          $row = mysqli_fetch_array($data);
          $_SESSION['hm_id'] = $row['hm_id'];
          $_SESSION['id_proof'] = $row['id_proof'];
          setcookie('id', $row['hm_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
          setcookie('id_proof', $row['id_proof'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/hm_index.php';
          header('Location: ' . $home_url);
          }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = ' "Sorry, you must enter a valid username and password to log in." ';
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = ' "Sorry, you must enter your username and password to log in." ';
      }
    }
  }
?>


<?php
  if (empty($_SESSION['hm_id'])) {
    echo '<p class="error" align="center"><strong>' . $error_msg . '</strong></p>';
     $error_msg = " ";
?>

		<form class="form_l" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" autocomplete="off">
			<div class="login">
				<h2>Login here...<span style="float:right; margin-right:40px;"> To Feel <em>"The <em>Help"</em></span></h2>
				
				<div style="margin-left:40px;">
                    <label for="idproof_password">Enter your <em><strong>Email Id</strong></em> & <em><strong>Password</strong></em> ~</label>
					<input id="l-idproof" type="email" name="idproof" value="<?php if (!empty($user_username)) echo $user_username; ?>" maxlength="40" placeholder="Email-Id" title="Your Email-Id" autofocus required="required" />
					<input id="l-password" type="password" name="password" maxlength="16" placeholder="Password" minlength="8" maxlength="15" title="It should be of 8 to 15  characters" required="required" />
					<input name="login" type="submit" title="Click me, to login into your home page" value=" Go ! "  />
				</div>
			</div>
		</form>
<?php
  }
  elseif(isset($_SESSION['hm_id'])) {
    echo('<p class="login" align= "center"><br><br>You are logged in as <strong> ' . $_SESSION['id_proof'] . '</strong>.<br><br>You can also logout here: <a href="logout.php" style="cursor:pointer">LogOut</a></p>');
  }
?>


<?php
	require_once('appvars.php');
	require_once('connectvars.php');
	
	$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	
	if(isset($_POST['signup'])){
		$_SESSION['first_name'] = $first_name = mysqli_real_escape_string($dbc,trim($_POST['firstname']));
		$_SESSION['last_name'] = $last_name = mysqli_real_escape_string($dbc,trim($_POST['lastname']));
		$_SESSION['id_proof'] = $id_proof = mysqli_real_escape_string($dbc,trim($_POST['idproof']));
		$_SESSION['password'] = $password1 = mysqli_real_escape_string($dbc,trim($_POST['password1']));
		$password2 = mysqli_real_escape_string($dbc,trim($_POST['password2']));
		$sex = mysqli_real_escape_string($dbc,trim($_POST['sex']));
		
		if(!empty($first_name) && !empty($last_name) && !empty($id_proof) && !empty($password1) && !empty($password2) && !empty($sex)){
			if($password1 == $password2){
				$query = "SELECT * FROM hm_profile WHERE id_proof = '$id_proof'";
				
				$data = mysqli_query($dbc, $query);
                               
				
				if(mysqli_num_rows($data) == 0){
                                      
					$query = "INSERT INTO hm_profile(hm_id, first_name, last_name, id_proof, password, gender_id, date_of_join) VALUES" .
							 "( 0, '$first_name', '$last_name', '$id_proof', SHA('$password1'), '$sex', NOW())";
					mysqli_query($dbc, $query);
		
					$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/hm_profile.php';
                                        header('Location: ' . $home_url);	
                                        mysqli_close($dbc);
					exit();	
				}
		        	else{
					echo'<p class="error" align="center"><strong>An <b>account already exits</b> for this E-mail address. Please use a different address.</strong></p>';
					$id_proof="";
			        }
                               if(mysqli_num_rows($data) == 1){
                                        $row = mysqli_fetch_array($data);
                                       
                                        setcookie('pro_id', $row['hm_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                                        setcookie('id_proof', $row['id_proof'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
                                        setcookie('pro_firstname', $row['first_name'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                                        setcookie('pro_lastname', $row['last_name'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                                        setcookie('pro_password', $row['password'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                               }
			}
			else{
				echo'<p class="error">You must enter the <b>same password</b> twice.<?p>';
			}
		}
		else{
			echo'<p class="error">You must enter <b>all</b> of the signup data.<?p>';
		}
	}
	mysqli_close($dbc);	
?>



		<form class="form_s" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" autocomplete="off">
			<div class="signup">
					<marquee><p>If your are a new comer, then <em>sign up</em> below.</p></marquee>
					<h2 align="left">Sign Up</h2>
					<ul>
						<li title="Enter your Name, which will be taken as Username">
							<label for="name">Enter <em><strong>Name</strong></em> ~</label>
							<input  type="text" id="firstname" name='firstname' value="<?php if(!empty($first_name)) echo $first_name;?>" maxlength="20" placeholder="First Name" title="Enter your first name, like :- 'Rahatullah'" required/>
							<input type="text" id="lastname" name='lastname' value="<?php if(!empty($last_name)) echo $last_name;?>" maxlength="20" placeholder="Last Name" title="Enter your last name, like :- 'Ansari'" required/>
						</li>
						
						<li title="Enter your Email-Id or Mobile no., which will be taken as ID-Proof">
							<br />
							<label for="s-idproof">Enter <em><strong>Email-Id</strong></em> ~ </label>
							<input type="email" id="idproof" name='idproof' value="<?php if(!empty($id_proof)) echo $id_proof;?>" size="20" maxlength="40" placeholder="Place for ID" title="Like :- 'abcd@efgh.ijk'" required="required"/>
						</li>
						
						<li title="Enter the Password, which you can be easily remembered in Future">
							<br />
							<label for="s-password">Enter <em><strong>Password</strong></em> ~ </label>
							<input type="password" id="password1" name='password1' minlength="8" maxlength="15" placeholder="It should be of 8 to 15 Digits" required/>
						</li>
						
						<li title="Enter the same Password, as entered above.">
							<br />
							<label for="s-rpassword">Re-enter <em><strong>Password</strong></em> ~ </label>
							<input type="password" name='password2' minlength="8" maxlength="15" placeholder="It should be same as above" required/>
						</li>
						
						<li title="Select your Gender.">	
							<br />
							
							<div name='sex' class="gender" data-type="radio" data-name="gender_tie" id="gender">
								<label>Select your <em><strong>Gender</strong></em> ~ </label></br>
								<div class="f">
									<input id="female" type="radio" value="0" name='sex' >Female
								</div>
								<div class="m">
									<input id="male" type="radio" value="1" name='sex'>Male
								</div>
							</div>
						</li></br>
						<li title="After checking all the enteries correctly, Click on the Checkbox to conform checking.">
							<br>
							<input id="check" name="check" type="checkbox" value="check" required/><strong>I have checked all enteries correctly</strong>
					
							<br><br />
						
							<input name="signup" title="Click me if above SignUp form entery is filled, to feel your new 'HELPMIII' Home page..." type="submit"   value=" Get In ! "  />
						</li>
					</ul>
			</div>	
		</form>

	</div>
</body>
</html>




















