<?php 

DEFINE ('APP_HOST_ADDRESS', 'http://grabitl.akqa.pp.ua');
DEFINE ('APP_NAME', 'GrabIt-L');

ob_start();

?>
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
	<head>
		<title>GrabIt-L</title>
		<meta charset="utf-8" />
		<link href="reset.css" rel="stylesheet" type="text/css" />
		<link href="main.css" rel="stylesheet" type="text/css" />
	</head>
	
	<body>	
<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	
	include_once 'functions.php';
		
	if (isset($_GET['u'])) {		
		Logout();
	} else {	
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'signin' : 
					if (Login($_POST['signin_login'], $_POST['signin_password'])) {
						
						if ($_POST['pagetoadd_link'] != '')
							AddPageFromUser($_POST['signin_login'], $_POST['pagetoadd_link'], $_POST['pagetoadd_title']);
							
						header("Location: /");
						return;
					} else {
						echo "<script>alert('Login/password is incorrect');</script>";
					}
					break;
				case 'register' : 
					if (Register($_POST['register_login'], $_POST['register_password'])) {
						
						if ($_POST['pagetoadd_link'] != '')
							AddPageFromUser($_POST['register_login'], $_POST['pagetoadd_link'], $_POST['pagetoadd_title']);
						
						//header("Location: /");
						return;
					} else {
						echo "<script>alert('This user is already exist');</script>";
					}
					break;
				case 'listactions' :
					$nIDToDelete = array_keys($_POST['delete']);
					DeletePage(GetAlreadyLogined(), array_pop($nIDToDelete));
					header("Location: /");
					break;
				case 'signout' : 
					Logout();
					header("Location: /");
					break;
			}
		}
	}
	
	$sCurrentUserName = GetAlreadyLogined();
?>		
		<div class="header">			
			<div class="left">
				<p>				
					<a href="/"><span class="sitename black">Grab</span><span class="sitename grey">It<img src="robber_32.png" alt="GrabIt-L"><span class="sitename black">L</span></a>
					<span class="motto">grab pages and read later</span>
				</p>
			</div>
			
			<div class="right">				
				<?php 					
					if ($sCurrentUserName) { ?>							
						<!-- logined -->
						<form method="POST" action="index.php" name="signout">
							<p>Welcome <span class="userlogin"><?php echo $sCurrentUserName?></span>!</label>
							<input type="submit" value="Log out"></p>
							<input type="hidden" name="action" value="signout">
							<input type="hidden" name="pagetoadd" value="<?php echo isset($_GET['p']) ? $_GET['p'] : ''?>">
						</form>
						<!-- eo logined -->
				<?php } else { ?>
						<!-- not logined -->
						<p>Welcome, guest! Please sign in or register new account to begin use <span class="grabit-l">GrabIt-L</span>
						<!-- eo not logined -->
				<?php } ?>
			</div>
			<div class="clear"></div>			
		</div>
				
		<div class="main-container">
			<h1>What is the <?php echo APP_NAME; ?>?</h1>
			<p class="desc"><acronym title="sounds as 'robber' in russian :)">GrabIt-L</acronym> just is a page aggregator that provide you a bookmarklet which will store (for further reading) link at any page you pressed within. Pages are stored in GrabIt-L page, so you don't need to syncronize bookmarks in all your devices.</p>
		</div>
		
		<?php if (!IsAlreadyLogined()) { ?>
			<div class="main-container">							
				<!-- register or login block -->
				<div class="left">
					<div class="signin-register right-bordered">
						<h2>Sign in</h2>
						<p class="desc">If you already have account at this site, please enter using your email and password</p>
						<fieldset>
							<form method="POST" action="index.php" name="signin">
								<p>
									<input type="email" name="signin_login" placeholder="Email" required value="<?php echo isset($_GET['u']) ? $_GET['u'] : ''?>">
									<input type="password" name="signin_password" placeholder="Password" required>
								</p>
								<p>
									<input type="submit" value="Enter">
								</p>
								<input type="hidden" name="action" value="signin">
								<input type="hidden" name="pagetoadd_link" value="<?php echo isset($_GET['p']) ? $_GET['p'] : ''?>">
								<input type="hidden" name="pagetoadd_title" value="<?php echo isset($_GET['t']) ? $_GET['t'] : ''?>">
							</form>
						</fieldset>
					</div>
				</div>		
				
				<div class="right">
					<div class="signin-register">
						<h2>Register</h2>
						<p class="desc">If you do not have account, please fill these fields and account will be created. It's free.</p>
						<fieldset>
							<form method="POST" action="index.php" name="register">
								<p>
									<input type="email" name="register_login" placeholder="Email" required>
									<input type="password" name="register_password" placeholder="Password" required>
								</p>
								<p>
									<input type="submit" value="Register">
								</p>
								<input type="hidden" name="action" value="register">
								<input type="hidden" name="pagetoadd_link" value="<?php echo isset($_GET['p']) ? $_GET['p'] : ''?>">
								<input type="hidden" name="pagetoadd_title" value="<?php echo isset($_GET['t']) ? $_GET['t'] : ''?>">
							</form>
						</fieldset>
					</div>
				</div>
				<!-- eo register or login block -->
				
				<div class="clear"></div>
			</div>
		<?php } else {?>			
			<div class="main-container">
				<h2>Your personal bookmarklet</h2>
				<p class="desc">Here is your personal bookmarklet <br /> <br />
					<a href="javascript:function%20grabitl(){if(!document.body){alert('not_loaded_yet');return;}var%20el=document.createElement('scr'+'ipt');el.setAttribute('src','<?php echo APP_HOST_ADDRESS;?>/process.php?u=<?php echo $sCurrentUserName?>&p='+encodeURIComponent(document.location.href)+'&t='+encodeURIComponent(document.title));document.body.appendChild(el);};grabitl();void(0);"><img src="robber_bookmarklet.png" title="<?php echo APP_NAME; ?>" alt="<?php echo APP_NAME; ?>"></a>
					<br /> <br />
					Drag it at your bookmarks and press when you want to save page! Enjoy!
				</p>
			</div>
			
			<div class="main-container">		
				<!-- articles list block -->
				<div class="articles-list">
					<form method="POST" action="index.php" name="listactions">
						<p class="bottom-bordered">Your stored pages: </p>
						
							<?php 
								$aUserData = GetUserDataFromDB($sCurrentUserName);
								if ($aUserData && count($aUserData['pages'])) {								
									echo '<ol type="1">';
									foreach($aUserData['pages'] as $k => $v) {
										echo '<li><span class="ext-link"><a href="'.$v['url'].'" target="_blank">'.$v['title'].'</a></span><span class="time">'.$v['time'].'</span><input type="submit" name="delete['.$k.']" value="Remove"></li>';
									}
									echo '</ol>';
								} else {
									echo '<p class="desc">There is no saved pages yet!</p>';
								}
							?>
						<input type="hidden" name="action" value="listactions">
					</form>
				</div>
				<!-- eo articles list block -->			
			</div>
		<?php } ?>
		
		<div class="footer">
			<div>
		</div>
	</body>	
</html>
<?php ob_end_flush(); ?>

