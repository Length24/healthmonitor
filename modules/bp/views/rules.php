<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 14/09/2022
 * Time: 12:50
 */

?>
<h1>Gift</h1>
<h3>Raw Game</h3>
<p>Ready Player 1 Code challenge is based on a 50*50 grid (not absolutely true, but as a good coder you can hopefully work out why).</p>p>
<p>When you sign up for an account, you select a colour; this is your colour for the rest of the game time (roughly one week from the game start).  Initially just clicking on the game board grid will change the square of the grid to your colour.</p>
<ol>
    <li>Clicking on all four corners of the grid will award you some points.</li>
    <li>Filling in all the edges will also award some points.</li>
    <li>And finally controlling every space will award some points.</li>
</ol>
<p>Assuming nobody else changes them</p>
<h3>Tick and Degradation</h3>
Every half hour the following will happen:
<ol>
    <li>Anybody achieving the above scoring items at the tick will gain some points…</li>
    <li>Everybody will get some points for every square they control.</li>
    <li>Degradation will happen by removing roughly 1 in 20 squares from the board.</li>
</ol>
<h3>Gifting</h3>
<p>If another player is particularly impressed with what you have done on the board, they can gift you some of their score, using the gifting menu.</p>
<h3>The API</h3>
<blockquote class="blockquote">“Hang on… are you saying that every half hour I am going to need to log in and updated my colour and then keep an eye on it, to make sure nobody else is over-ruling me!”</blockquote>
<p>IIt should be apparent that over a week, just clicking on tiles is not going to be very effective.  Therefore an API exists to allow players to automate their clicks, using which ever language they wish.</p>
<p>The API has the following functionality:</p>
<ol>
    <li>Allows you to change a square to your colour</li>
    <li>Allows you to change a square to somebody else’s colour (useful if you are playing for gifts by just making pretty things.. this is only possible via the APi).</li>
    <li>Allows you to pull which colour and player controls a cell</li>
    <li>Allows you to pull an array with all cells and owners.</li>
</ol>
<p>
This should allow you to automate the flow of the board insuring that you score the most points, stop somebody else scoring the most points, make the prettiest picture (Delete as appropriate).</p>




