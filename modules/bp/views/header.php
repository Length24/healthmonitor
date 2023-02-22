<?php

$cookies = Yii::$app->request->cookies;
$username = null;
$color = "#FFFFFF";
if (isset($cookies['player'])) {
    $username = $cookies['player']->value;
}
if (isset($cookies['color'])) {
    $color = $cookies['color']->value;
}

?>
<!doctype html>
<html lang="en">
<head>
<style>
    .box {
        float: left;
        height: 20px;
        width: 20px;
        margin-bottom: 15px;
        border: 1px solid black;
        clear: both;
        background-color: <?=$color?>
    }
    .scroll-div {
        float: left;
        width: 1000px;
        overflow-y: auto;
        height: 600px;
    }


</style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ready Player Code Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
</head>
<body>


<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/v1/game/game/">Code Challenge</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a style ="margin-right:20px; border-right: 2px solid #ced4da; margin-left:20px; border-left: 2px solid #ced4da;" class="nav-link active" aria-current="page" href="/v1/game/game/signup/">Sign Up</a>
                </li>
                <li class="nav-item" style ="margin-left:20px; border-left: 2px solid #ced4da;">
                    <a class="nav-link active" aria-current="page" href="/v1/game/game/">Game Board</a>
                </li>

                <li class="nav-item dropdown" style ="margin-right:20px; border-right: 2px solid #ced4da;">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Docs and Scores
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/v1/game/game/rules/">Game Rules</a></li>
                        <li><a class="dropdown-item" href="/v1/game/game/apidocs/">Profile and Links</a></li>
                        <li><a class="dropdown-item" href="/v1/game/game/gifts/">Gift to another player</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/v1/game/game/scoreboard/">Scoreboard</a></li>
                    </ul>
                </li>


            </ul>

            <?php  if ($username != null) { ?>
            <div class='box'></div> Ready Player <?= $username?> | <a href="/v1/game/game/logout/"> Log Out </a>
            <?php } else {  ?>

            <div class="bootstrap-iso">
            <form method="post" class="d-flex" action="/v1/game/game/">
                <div class="form-group ">
                    <label class="control-label " for="name">
                        Username
                    </label>
                    <input class="form-control" id="username" name="username" type="text"/>
                </div>
                <div class="form-group ">
                    <label class="control-label " for="password">
                        Password
                    </label>
                    <input class="form-control" id="password" name="password" type="password" aria-hidden="true"/>
                </div>
                <div class="form-group">
                    <div>
                        <button class="btn btn-primary " name="submit" type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
            </div>
            <?php } ?>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php if ($message != '') { ?>
                <div class="alert alert-primary" role="alert" style="text-align: center;">
                    <?= $message ?>
                </div>
            <?php } ?>

        </div>
        <div class="col-md-8" style ="padding: 30px">

