<?php
session_start();
require_once "pdo.php";
$row = get_entry('profile') ;  // retrieving the entry from db by id from GET
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Maksim Mislavskii's Resume Registry - Profile details</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
      <h1>Profile information</h1>
      <p>First Name: <?= htmlentities( $row['first_name'] ) ?></p>
      <p>Last Name: <?= htmlentities( $row['last_name'] ) ?></p>
      <p>Email: <?= htmlentities( $row['email'] ) ?></p>
      <p>Headline:<br/>
      <?= htmlentities( $row['headline'] ) ?></p>
      <p>Summary:<br/>
      <?= htmlentities( $row['summary'] ) ?><p>
      </p>
      <a href="index.php">Done</a>
    </div>
  </body>
</html>
