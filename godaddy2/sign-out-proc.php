<?php
@ob_start();
session_start(); 
unset($_SESSION);
session_destroy();
?>
<script type="text/javascript">
window.location.href = 'sign-in.php';
</script>