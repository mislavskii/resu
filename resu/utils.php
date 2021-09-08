<?php
function no_login_or_cancel() {
    if ( ! isset($_SESSION['name']) ) {  // user not logged in
        die('Not logged in');
    }
    if ( isset($_POST['cancel']) ) {  // user clicked Cancel
        header ( 'Location: index.php' ) ;  // skip back to index.php
        exit;
    }
}

?>
