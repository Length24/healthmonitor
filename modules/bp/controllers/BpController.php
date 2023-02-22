<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 12/09/2022
 * Time: 12:11
 */

namespace app\modules\bp\controllers;

use app\models\readyplayer1\ReadyPlayer1;
use app\models\readyplayer1\ReadyPlayer1Board;
use app\modules\game\models\Game;
use app\modules\game\Module;
use app\modules\user\controllers\PaymentController;
use Yii;

use yii\web\Controller;


class BpController extends Controller
{
    private $headerAlertMessage = '';

    public function beforeAction($action)
    {
        return true;
    }

    public function actionIndex()
    {
        $post = Yii::$app->request->post();

        $get = Yii::$app->request->get();

        if(isset($get['r'])) {
            $this->updateColor($get);
        }

        if (!empty($post) && isset($post['username'])) {
            $this->login();
        }

     /* //  $colours = ReadyPlayer1Board::find()->all();
        $color = [];
        foreach ($colours as $cell) {
            $color[$cell->c][$cell->r] = $cell->color;
        }*/

        return $this->createPage('/index', []);


    }

    public function updateColor($get) {
        $c = $get['c'];
        $r = $get['r'];
        $cookies = Yii::$app->request->cookies;
        if (isset($cookies['color'])) {
            $cell = ReadyPlayer1Board::findOne(['c' => $c, 'r' => $r]);

            $cell->color = $cookies['color']->value;
            $cell->owner = $cookies['ownerId']->value;
            $cell->save();
            $game = new Game();
            $game->scoreUp();
        }
    }

    private function createPage($page = '/index', $pageParams = []) {
        $game = new Game();
        $header = $this->render('/header', ["message" => $this->headerAlertMessage]);
        $footer = $this->render('/footer', ["scores" => $game->getScores()]);

        return $header . $this->render($page, $pageParams) . $footer;
    }

    public function actionRules() {
        return $this->createPage('/rules', []);
    }

    public function actionGiftscore()
    {
        $game = new Game();
        $result = $game->giftScore();
        $this->headerAlertMessage = "failed to add score";
        if($result == true) {
            $this->headerAlertMessage = "Score has been added";
        }

        return $this->createPage('/gift', ['players' => $game->getScores()]);
    }

    public function actionGifts() {
        $game = new Game();
        return $this->createPage('/gift', ['players' => $game->getScores()]);
    }

    public function actionScoreboard()
    {
        $game = new Game();
        return $this->createPage('/scoreboard', ['scores' => $game->getScorebreakdown()]);
    }

    public function Login()
    {

        $post = Yii::$app->request->post();
        $user = ReadyPlayer1::findOne(['username' => $post['username']]);
        if ($user == false) {
            $this->headerAlertMessage = "User does not exist";
        } else {
            ;
            if (password_verify($post['password'], $user['password'])) {

                $cookies = Yii::$app->response->cookies;
                $cookies->readOnly = false;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'color',
                    'value' => $user['color'],
                    'expire' => time() + 86400 * 365,
                ]));

                $cookies->add(new \yii\web\Cookie([
                    'name' => 'player',
                    'value' => $user['username'],
                    'expire' => time() + 86400 * 365,
                ]));

                $cookies->add(new \yii\web\Cookie([
                    'name' => 'ownerId',
                    'value' => $user['id'],
                    'expire' => time() + 86400 * 365,
                ]));


            } else {
                $this->headerAlertMessage = "Incorrect Password";
            }
        }

    }

    public function actionApidocs()
    {
        $cookies = Yii::$app->request->cookies;

        $owner = null;
        if (isset($cookies['ownerId'])) {
            $owner = $cookies['ownerId']->value;
        }

        $player = ReadyPlayer1::find()->where(['id' => $owner] )->One();

        return $this->createPage('/apidocs', ['player' => $player ]);

    }


    public function actionSignup()
    {
        $post = Yii::$app->request->post();
        if (isset($post['username']) && $post['username'] != '') {

            $signup = ReadyPlayer1::findOne(['username' => $post['username']]);
            if ($signup != false) {
                $this->headerAlertMessage = "Username already exists";
            } else {
                $signup = ReadyPlayer1::findOne(['color' => $post['color']]);
                if (strlen($post['password']) > 7) {
                    if ($signup == false) {
                        $cookies = Yii::$app->response->cookies;
                        $cookies->readOnly = false;
                        $cookies->remove('player');
                        $cookies->remove('color');
                        $cookies->remove('ownerId');

                        $signup = new ReadyPlayer1();
                        $signup->key = $this->rand_key();
                        $signup->username = $post['username'];
                        $signup->realname = $post['realname'];
                        $signup->color = $post['color'];
                        $signup->password = password_hash($post['password'], PASSWORD_DEFAULT);
                        $signup->save();

                        $cookies->add(new \yii\web\Cookie([
                            'name' => 'color',
                            'value' => $signup->color,
                            'expire' => time() + 86400 * 365,
                        ]));

                        $cookies->add(new \yii\web\Cookie([
                            'name' => 'player',
                            'value' => $signup->username,
                            'expire' => time() + 86400 * 365,
                        ]));

                        $cookies->add(new \yii\web\Cookie([
                            'name' => 'ownerId',
                            'value' => $signup->id,
                            'expire' => time() + 86400 * 365,
                        ]));

                        $this->headerAlertMessage = "Player Has been Created";
                    } else {
                        $this->headerAlertMessage = "Color has already taken";
                    }
                } else {
                    $this->headerAlertMessage = "Password needs to be over 8 letters long";
                }
            }
        }

        return $this->createPage('/signup');
    }


    public function actionLogout() {
        $cookies = Yii::$app->response->cookies;
        $cookies->readOnly = false;

        $cookies->remove('player');
        $cookies->remove('color');
        $cookies->remove('owner');
        $this->redirect('/v1/bp/bp/');
    }

    public function actionSetupnewgame()
    {
        for ($c = 0; $c <= 50; $c++) {
            for ($r = 0; $r <= 50; $r++) {
                $cell = new ReadyPlayer1Board();
                $cell->c = $c;
                $cell->r = $r;
                $cell->owner= 1;
                $cell->color= "#000000";
                $cell->save();
            }
        }
    }

    Private function rand_key() {
        return  str_pad(dechex(mt_rand(0, 0xFFFFFFFFFF)), 11, '0', STR_PAD_LEFT);
    }

}