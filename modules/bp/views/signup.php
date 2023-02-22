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
                        <label class="control-label " for="password">
                            New Password
                        </label>
                        <ol>
                        <li id="pwd-restriction-length">Be between 10-16 characters in length</li>
                        <li id="pwd-restriction-upperlower">Contain at least 1 lowercase and 1 uppercase letter</li>
                        <li id="pwd-restriction-number">Contain at least 1 number (0–9)</li>
                        <li id="pwd-restriction-special">Contain at least 1 special character (!@#$%^&()'[]"?+-/*)</li>
                        </ol>
                        <label class="control-label " for="password">
                            Set a Password
                        </label>
                        <input class="form-control" id="password" name="password" type="password" aria-hidden="true"/>
                        <label class="control-label " for="password">
                            Re-enter Password
                        </label>
                        <input class="form-control" id="password2" name="password2" type="password" aria-hidden="true"/>
                    </div>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary " name="submit" type="submit">
                                Sign-Up
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
