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

<div class="bootstrap-iso">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <form method="post">
                    <div class="form-group">
                        <h3>
                            Setup New user account
                        </h3>
                        <div id="message">
                            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                            <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                            <p id="number" class="invalid">A <b>number</b></p>
                            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                            <p id="matchPass" class="invalid"><b>Passwords Match</b></p>
                            <p id="matchUser" class="invalid"><b>Username not blank</b></p>
                            <p id="matchEmail" class="invalid">Email <b>is a valid email address</b></p>
                        </div>
                        <label class="control-label " for="ewusername">
                            Username
                        </label>
                        <input class="form-control" id="newusername" name="newusername" type="text"/>

                        <label class="control-label " for="newmail">
                            Email
                        </label>
                        <input class="form-control" id="newemail" name="newemail" type="text"/>
                    </div>
                    <div class="form-group ">

                        <label class="control-label " for="password">
                            Set a Password
                        </label>
                        <input class="form-control" id="newpassword" name="newpassword" type="password" aria-hidden="true"/>
                        <label class="control-label " for="password2">
                            Re-enter Password
                        </label>
                        <input class="form-control" id="password2" name="password2" type="password" aria-hidden="true"/>
                    </div>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary " name="submit" id = "btnSubmit" type="submit" disabled>
                                Sign-Up
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() { // this will be called when the DOM is ready

        $( "#newpassword" ).keyup(function() {
            var inputString = $("#newpassword").val();
            validate(inputString);
            turnOnButton()
        });

        $( "#password2" ).keyup(function() {
            var inputString2 = $("#password2").val();
            validateMatch(inputString2);
            turnOnButton()
        });

        $( "#newemail" ).keyup(function() {
            var inputString2 = $("#newemail").val();
            validateMatch(inputString2);
            turnOnButton()
        });

        $( "#newusername" ).keyup(function() {
            var inputString3 = $("#newusername").val();
            validateMatch(inputString3);
            turnOnButton()
        });
    });

    function validateMatch(password) {
        let pas1 = $("#newpassword").val();
        let pas2 = $("#password2").val();
        let passmatch = $( "#matchPass");

        if(pas1 === pas2 && pas1 !== '') {
            passmatch.addClass( "valid" );
            passmatch.removeClass( "invalid" );
        } else {
            passmatch.removeClass( "valid" );
            passmatch.addClass( "invalid" );
        }

        let user = $("#newusername");
        let userdiag = $("#matchUser");

        if(user.val() !== "") {
            userdiag.addClass( "valid" );
            userdiag.removeClass( "invalid" );
        } else {
            userdiag.removeClass( "valid" );
            userdiag.addClass( "invalid" );
        }

        let email = $("#newemail").val();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        let emailcheck = $( "#matchEmail" );

        if (regex.test(email)) {
            emailcheck.addClass( "valid" );
            emailcheck.removeClass( "invalid" );
        } else {
            emailcheck.removeClass( "valid" );
            emailcheck.addClass( "invalid" );
        }


        return regex.test(email);
    }

    function turnOnButton() {
        if( $("#number").hasClass("valid")
            && $("#length").hasClass("valid")
            && $("#letter").hasClass("valid")
            && $("#capital").hasClass("valid")
            && $("#matchUser").hasClass("valid")
            && $("#matchPass").hasClass("valid")
            && $("#matchEmail").hasClass("valid")
        ) {
            $("#btnSubmit"). attr("disabled", false);
        } else {
            $("#btnSubmit"). attr("disabled", true);
        }
    }

    function validate(password) {
        var minMaxLength = /^[\s\S]{8,32}$/,
            upper = /[A-Z]/,
            lower = /[a-z]/,
            number = /[0-9]/,
            special = /[ !"#$%&'()*+,\-./:;<=>?@[\\\]^_`{|}~]/;

        let numbercheck = $( "#number" );

        if (number.test(password)) {
            numbercheck .addClass( "valid" );
            numbercheck .removeClass( "invalid" );
        } else {
            numbercheck .removeClass( "valid" );
            numbercheck .addClass( "invalid" );
        }

        let minMaxLengthcheck = $( "#length" );
        if (minMaxLength.test(password)) {
            minMaxLengthcheck.addClass( "valid" );
            minMaxLengthcheck.removeClass( "invalid" );
        } else {
            minMaxLengthcheck.removeClass( "valid" );
            minMaxLengthcheck.addClass( "invalid" );
        }

        let lowercheck = $( "#letter" );
        if (lower.test(password)) {
            lowercheck.addClass( "valid" );
            lowercheck.removeClass( "invalid" );
        } else {
            lowercheck.removeClass( "valid" );
            lowercheck.addClass( "invalid" );
        }

        let uppercheck = $( "#capital" );
        if (upper.test(password)) {
            uppercheck.addClass( "valid" );
            uppercheck.removeClass( "invalid" );
        } else {
            uppercheck.removeClass( "valid" );
            uppercheck.addClass( "invalid" );
        }

        validateMatch(password);
    }

</script>