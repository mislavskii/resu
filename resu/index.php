<?php // TODO: JS validation ?>

<?php
session_start();
require_once "pdo.php";

$stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name,
    headline FROM profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Maksim Mislavskii's Resume Registry</title>
  <?php require_once "bootstrap.php"; ?>
  <style media="screen">
    td, th {
      padding: 0.7em;
    }
    <?php
    if ( !isset($_SESSION['name']) ) {  // user not logged in
        echo '.action { display: none; }' ; // no Action column for them
    }
    ?>
  </style>
</head>
<body>
<div class="container">

<h1>Maksim Mislavskii's Resume Registry</h1>

<p>
  <?php // flash messages
  if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
  }

  if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
  }
  ?>
</p>
<?php  // profiles table
echo '<table border="1">'."\n";
echo '<thead>';
echo '<th>Name</th><th>Headline</th><th class="action">Action</th>';
echo '</thead>'."\n";
foreach ( $rows as $row ) {
    echo "<tr>";
    echo ('<td>');
      echo ('<a href="view.php?profile_id='.$row['profile_id'].'">');
        echo (htmlentities($row['first_name'].' '.$row['last_name']));
      echo ('</a>');
    echo ('</td>');
    echo ('<td>'.htmlentities($row['headline']).'</td>');
    echo ('<td class="action">');
    if ( isset($_SESSION['name']) && $_SESSION['user_id'] == $row['user_id'] ) { // record belongs to current user
      // show edit and delete links
      echo ('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
      echo ('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    }
    echo ("</td>");
    echo "</tr>\n";
}
echo "</table></br>\n";
?>

<?php
if ( isset($_SESSION['name']) ) {  // user logged in
    echo '<p><a href="add.php"><strong>Add New Entry</strong></a></p>' ; // let them add new profile
    echo '<p><a href="logout.php"><strong>Logout</strong></a></p>' ;  // or logout
} else {  // invite them to log in
    echo ('<p><a href="login.php"><strong>Please log in</strong></a></p>') ;
}
?>

</div>
</body>
</html>
