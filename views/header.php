<!doctype html>

<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="http://localhost/SocialMedia_Site/styles.css">

    <title>/Logr/</title>
  </head>

  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-blue">
    <a class="navbar-brand" href="index.php">/Logr/</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
      <?php if (isset($_SESSION['id'])) {?>
        <li class="nav-item">
          <a class="nav-link" href="?page=feed">Feed</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?page=yourPosts">Your Posts</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="?page=publicProfiles">Find Friends</a>
        </li>
        <?php }?>
      </ul>
      <div class="form-inline pull-xs-right">

      <?php if (isset($_SESSION['id'])) { ?>

      <a class="btn btn-outline-success" href="?function=logout">Logout</a>
      <?php } else {?>
     	 
     	 <button class="btn btn-outline-success" data-toggle="modal" data-target="#loginModal">Login/Signup</button>
     	 
     	 <?php }?>
    </div>
  </nav>

