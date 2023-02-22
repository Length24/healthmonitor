<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 13/09/2022
 * Time: 13:21
 */?>
<div class="row">
    <div class="col-md-12" style ="text-align: center">
        <h1>Profile and useful links</h1>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h2>User Details</h2>
            <h5>
                <ol>
                    <li>Username: <?=$player->username?></li>
                    <li>Realname: <?=$player->realname?></li>
                    <li>Api Key: <?=$player->key?></li>
                </ol>
            </h5>

            <h2>End Points Information</h2>
            <a href = "https://www.getpostman.com/collections/737567da9530bbe73b2a">Postman collection</a><br />

            <a href = "https://documenter.getpostman.com/view/8837617/2s7YYvYgie"> API documentation </a>
        </div>
        <div class="col-md-8">
            <h2>Free Web providers (none of these have been checked)</h2>
            If you want to deploy your own web-app as part of the challenge a few free options are below (there is loads more though)<br /><br />
            However no need if you don't want the hassle, last time I took part in something similar, I wrote it, in VB.net and just kept it running on my home PC!<br />
            <ul>
                <li><a href = "https://vercel.com/">vercel (JS frameworks)</a></li>
                <li><a href = "https://www.pythonanywhere.com/"> Python Anywhere </a></li>
                <li><a href = "https://www.heroku.com/"> heroku (Node.jd, Java, PHP, Python and others( </a></li>
                <li> <a href = "https://www.a2hosting.com/hosting"> A2 Hosting (PHP etc) </a></li>
                <li> <a href = "https://somee.com/FreeAspNetHosting.aspx">Somee.com (Asp.net) </a></li>
                <li><a href = "https://www.brinkster.com/hosting/freedeveloper.aspx"> /freedeveloper ASP hosting</a></li>
            </ul>

        </div>
    </div>
</div>