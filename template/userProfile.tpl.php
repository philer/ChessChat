			<section style="padding-top: 10%;">
				TODO: user profile<br />
				<a href="index.php/User/logout">» logout</a><br /><br />
<?php
// get data
$user = isset($this->var['user']) ? $this->var['user'] : Core::getUser();

// TODO
echo '<pre>';
var_dump($user);
echo '</pre>';
?>

			</section>
