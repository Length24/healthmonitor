<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 12/09/2022
 * Time: 13:45
 */
?>


<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

<!-- Inline CSS based on choices in "Settings" tab -->

<!-- HTML Form (wrapped in a .bootstrap-iso div) -->
<div class="bootstrap-iso">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <form method="post">
                    <div class="form-group ">
                        <label class="control-label " for="name">
                            Username
                        </label>
                        <input class="form-control" id="username" name="username" type="text"/>
                    </div>
                    <div class="form-group ">
                        <label class="control-label " for="text1">
                            Real name (if not added account will be deleted)
                        </label>
                        <input class="form-control" id="realname" name="realname" type="text"/>
                    </div>
                    <div class="form-group ">
                        <label class="control-label " for="name1">
                            Color
                        </label>
                        <input style = "width:100px" class="form-control" id="color" name="color" type="color"/>
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
        </div>
    </div>
</div>
