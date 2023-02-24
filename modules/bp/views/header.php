<?php

$hostPage = "/bp/bp/";

$cookies = Yii::$app->request->cookies;
$username = null;
if (isset($cookies['user'])) {
    $username = $cookies['user']->value;
}

?>
<!doctype html>
<html lang="en">
<head>
    <style>
        .scroll-div {
            float: left;
            width: 1000px;
            overflow-y: auto;
            height: 600px;
        }

        .valid {
            color: green;
        }

        .valid:before {
            position: relative;

        }

        /* Add a red text color and an "x" icon when the requirements are wrong */
        .invalid {
            color: red;
        }

        .invalid:before {
            position: relative;

            content: "X ";
        }

        body {
            padding-bottom: 70px;
        }


    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Health Diary</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.3/journal/bootstrap.min.css"
          integrity="sha512-idjfPXQ6riP0BTBfHP0Suns3C1yZK486mMgm6Dd1pRQDizubroQ6EMZNP2S86C42mTOXCfKAQ/wcQ//Q3V2mQg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>


    <script src="https://use.fontawesome.com/c7e49cd2a1.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
            integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz"
            crossorigin="anonymous"></script>
</head>
<body>


<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $hostPage ?>">Health Diary</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($username === null) { ?>
                    <li class="nav-item">
                        <a style="margin-right:20px; border-right: 2px solid #ced4da; margin-left:20px; border-left: 2px solid #ced4da;"
                           class="nav-link active" aria-current="page" href="<?= $hostPage ?>signup/">Sign Up</a>
                    </li>
                <?php } ?>
                <?php if ($username !== null) { ?>
                    <li class="nav-item" style="margin-left:20px; border-left: 2px solid #ced4da;">
                        <a class="nav-link active" aria-current="page" href="<?= $hostPage ?>dailyupdate">Enter New
                            Health Information</a>
                    </li>

                    <li class="nav-item dropdown" style="margin-right:20px; border-right: 2px solid #ced4da;">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Reporting and Data Manipulation
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= $hostPage ?>edit/">View and edit past readings</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?= $hostPage ?>reporting/">Reporting and Graphs</a></li>
                            <li><a class="dropdown-item" href="<?= $hostPage ?>exports/">Exports</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?= $hostPage ?>faq/">FAQ</a></li>
                            <li><a class="dropdown-item" href="<?= $hostPage ?>apidocs/">API Docs</a></li>
                        </ul>
                    </li>

                <?php } ?>
            </ul>
        </div>
        <?php if ($username != null) { ?>
            Logged In user <?= $username ?> | <a href="<?= $hostPage ?>logout/"> Log Out </a>
        <?php } else { ?>

            <div class="bootstrap-iso">
                <form method="post" class="d-flex" action="/bp/bp/">
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
    </div>
    <div class="row">
        <div class="col-md-8" style="padding: 30px">

