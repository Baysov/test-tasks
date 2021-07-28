<?php

namespace test_tasks_secure_code\Views;

use test_tasks_secure_code\Libs\functions;
use test_tasks_secure_code\Controllers\account;

class main
{
 
  public static function start_settings_main() {
    
    if (!empty(account::$user_info['login']) 
           and account::$user_info['login']!='Anonymous') // automatic deletion of unused data
    functions::auto_delete_dirs_for_empty_tasks();

  }
  
}

main::start_settings_main();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Bootstrap contributors">
    <title>That is test tasks · Bootstrap v5.0</title>

    <link rel="canonical" href="<? print functions::https_host(); ?>/">

    <link href="<? print functions::https_host(); ?>/re-favicon_32x32.png" rel="shortcut icon" type="image/png" />
    <link href="<? print functions::https_host(); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<? print functions::https_host(); ?>/css/styles.css" rel="stylesheet" type="text/css">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
  </head>
  <body>
    
<header>
  <div class="collapse bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
          <h4 class="text-white">About</h4>
          <p class="text-muted">Add some information about the tasks below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand d-flex align-items-center">
        <img src="<? print functions::https_host(); ?>/css/task.ico" height="32" width="32">
        <h1 class="cls_h1">Tasks</h1>
      </a>
      <div id="id_tasks_general_ui" class="cls_tasks_general_ui"></div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
</header>

<main>

  <section class="pt-5 text-center container">

        <div id="id_general_ui" class="cls_general_ui"></div>
        <div id="id_login_form" class="cls_login_form"></div>
        
  </section>

</main>

<footer class="text-muted py-5">
  <div class="container">
    <p class="float-end mb-1">
      <a href="#">Back to top</a>
    </p>
    <p class="mb-1">Tasks example is © Bootstrap, but please download and customize it for yourself!</p>
    <p class="mb-0">This example uses the MVC architecture model with class autoloading.</p>
  </div>
</footer>

  <?php account::js_onload_config(); ?>

  </body>
</html>
