<div class="error">
<?php
if(isset($this->var['errorTitle'])) {
	echo "<h1>{$this->var['errorTitle']}</h1>";
}
if(isset($this->var['errorMessage'])) {
	echo "<p>{$this->var['errorMessage']}</p>";
}
?>
</div>
