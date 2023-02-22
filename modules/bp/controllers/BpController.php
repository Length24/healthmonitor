<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 12/09/2022
 * Time: 12:11
 */

namespace app\modules\bp\controllers;

use app\models\bp\Health;
use app\models\bp\users;
use app\models\readyplayer1\ReadyPlayer1;
use app\models\readyplayer1\ReadyPlayer1Board;
use app\models\User;
use app\modules\game\models\Game;
use app\modules\game\Module;
use app\modules\user\controllers\PaymentController;
use Yii;

use yii\web\Controller;


class BpController extends Controller
{
    public $controller = false;
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


    private function createPage($page = '/index', $pageParams = []) {

        $header = $this->render('/header', ["message" => $this->headerAlertMessage]);
        $footer = $this->render('/footer', ["scores" => 0]);

        return $header . $this->render($page, $pageParams) . $footer;
    }

    public function actionRules() {
        return $this->createPage('/rules', []);
    }

    public function actionGiftscore()
    {

        $result = $game->giftScore();
        $this->headerAlertMessage = "failed to add score";
        if($result == true) {
            $this->headerAlertMessage = "Score has been added";
        }

        return $this->createPage('/gift', ['players' => 0]);
    }

    public function actionGifts() {
        return $this->createPage('/gift', ['players' =>0]);
    }



    public function actionDailyupdate()
    {
        $post = Yii::$app->request->post();
        if(isset($post['sys'])) {
            $health = new Health();
            $health->sys = $post['sys'];
            $health->dia = $post['dia'];
            $health->pul = $post['pulse'];
            $health->step = $post['step'];
            $health->other = $post['other'];
            $health->datetime = $post['datesupplementary-123'].' '.$post['timesupplementary-123'];
            $health->save();
            $this->headerAlertMessage = "Added Reading";
        }

        return $this->createPage('/dailyupdate', ['scores' => 0]);
    }

    public function Login()
    {
        $post = Yii::$app->request->post();
        $user = users::findOne(['username' => $post['username']]);
        if ($user == false) {
            $this->headerAlertMessage = "User does not exist";
        } else {
            if (password_verify($post['password'], $user['password'])) {

                $cookies = Yii::$app->response->cookies;
                $cookies->readOnly = false;

                $cookies->add(new \yii\web\Cookie([
                    'name' => 'user',
                    'value' => $user['username'],
                    'expire' => time() + 86400 * 365,
                ]));

                $cookies->add(new \yii\web\Cookie([
                    'name' => 'id',
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

        return $this->createPage('/apidocs', ['player' => null ]);

    }


    public function actionSignup()
    {
        $post = Yii::$app->request->post();
        if (isset($post['newusername']) && $post['newusername'] != '') {
            $signup = Users::findOne(['username' => $post['newusername']]);
            if ($signup != false) {
                $this->headerAlertMessage = "Username already exists";
            } else {
                if (strlen($post['newpassword']) > 8) {
                    $cookies = Yii::$app->response->cookies;
                    $cookies->readOnly = false;
                    $cookies->remove('user');
                    $cookies->remove('ownerId');


                    $signup = new users();
                    $signup->key = $this->rand_key();
                    $signup->username = $post['newusername'];
                    $signup->email = $post['newemail'];
                    $signup->password = password_hash($post['newpassword'], PASSWORD_DEFAULT);
                    $signup->save();

                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'user',
                        'value' => $signup->username,
                        'expire' => time() + 86400 * 365,
                    ]));
                    $_SESSION['user'] = $signup->username;

                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'id',
                        'value' => $signup->id,
                        'expire' => time() + 86400 * 365,
                    ]));
                    $_SESSION['ownerId'] =$signup->id;
                    $this->headerAlertMessage = "User Has been Created";

                } else {
                    $this->headerAlertMessage = "Password needs to be over 8 letters long";
                }
            }
        } else {
            if(isset($post['newusername'])) {
                $this->headerAlertMessage = "No Username is set";
            }
        }

        return $this->createPage('/signup');
    }


    public function actionLogout() {
        $cookies = Yii::$app->response->cookies;
        $cookies->readOnly = false;

        $cookies->remove('user');
        $cookies->remove('id');
        $this->redirect('/bp/bp/');
    }

    public function actionSetupnewgame()
    {
    }

    Private function rand_key() {
        return  str_pad(dechex(mt_rand(0, 0xFFFFFFFFFF)), 11, '0', STR_PAD_LEFT);
    }

}