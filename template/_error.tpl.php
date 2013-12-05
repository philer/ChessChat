<div class="error"><?php
if(isset($this->var['errorTitle'])) {
    echo '<h1>' . $this->lang($this->var['errorTitle']) . '</h1>';
}
if(isset($this->var['errorMessage'])) {
    echo '<p>' . $this->lang($this->var['errorMessage']) . '</p>';
}
?></div>
