<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 13/09/2022
 * Time: 13:58
 */

namespace app\modules\bp\controllers;

use app\models\readyplayer1\ReadyPlayer1;
use Yii;
use app\modules\game\models\Game;
use app\models\readyplayer1\ReadyPlayer1Board;
use yii\web\Controller;

class ApiController extends Controller
{
    private $GameUser = false;

    public function beforeAction($action)
    {
       $headers = yii::$app->request->headers;
       if(isset($headers['game_key'])) {
           $this->GameUser = ReadyPlayer1::find()->where(['key' => $headers['game_key']])->one();
           if($this->GameUser == false) {
               $this->giveResponse(404, ["fail" => "Incorrect Game Key"]);
           }
           return true;
       }

       $this->giveResponse(404, ["fail" => "No Game_Key Set"]);
    }

    public function actionGetcellowner() {
        $get = yii::$app->request->get();
        if(isset($get['c']) && isset($get['r'])) {
            $c = $get['c'];
            $r = $get['r'];
            if ($c >= 0 && $c < 51) {
                if ($r >= 0 && $r < 51) {
                    $sql = "SELECT rp.username, rp1b.color, rp.id
                          FROM engwiki.readyplayer1board rp1b
                          INNER JOIN engwiki.readyplayer rp ON rp.id = rp1b.`owner`
                          WHERE rp1b.c = :Col AND rp1b.r = :row";
                    $result = Yii::$app->db->createCommand($sql, [':Col' => $c, ':row' => $r])->queryOne();

                    $this->giveResponse(200, ["data" => $result]);
                } else {
                    $this->giveResponse(404, ["fail" => "R is incorrect size - 0 to 50"]);
                }
            } else {
                $this->giveResponse(404, ["fail" => "C is incorrect size - 0 to 50"]);
            }
        } else {
                $this->giveResponse(404, ["fail" => "Params not set &c=1&r=2"]);
        }
    }

    public function actionGetallcells()
    {
        $sql = "SELECT rp.username, rp1b.color, rp.id, rp1b.c, rp1b.r 
                    FROM engwiki.readyplayer1board rp1b
                    INNER JOIN engwiki.readyplayer rp ON rp.id = rp1b.`owner`";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        $this->giveResponse(200, ["data" => $result]);
    }

    public function actionChangecell() {
        $get = yii::$app->request->get();
        if(isset($get['c']) && isset($get['r'])) {
            $c = $get['c'];
            $r = $get['r'];
            if ($c >= 0 && $c < 51) {
                if ($r >= 0 && $r < 51) {
                    $cell = ReadyPlayer1Board::findOne(['c' => $c, 'r' => $r]);
                    $cell->color = $this->GameUser['color'];
                    $cell->owner = $this->GameUser['id'];
                    $cell->save();
                    $game = new Game();
                    $game->scoreUp();

                    $this->giveResponse(200, ["data" => "Success, Cell updated"]);
                } else {
                    $this->giveResponse(404, ["fail" => "R is incorrect size - 0 to 50"]);
                }
            } else {
                $this->giveResponse(404, ["fail" => "C is incorrect size - 0 to 50"]);
            }
        } else {
            $this->giveResponse(404, ["fail" => "Params not set &c=1&r=2"]);
        }
    }

    public function actionSetanotherplayer() {
        $post = yii::$app->request->post();
        if(isset($post['c']) && isset($post['r']) && isset($post['userid'])) {
            if ($post['c'] >= 0 && $post['c'] < 51) {
                if ($post['r'] >= 0 && $post['r'] < 51) {
                    $otherplayer = ReadyPlayer1::find()->where(['id' => $post['userid']])->one();;
                    if ($otherplayer == false) {
                        $this->giveResponse(404, ["fail" => "User does not exist"]);
                    } else {
                        $cell = ReadyPlayer1Board::findOne(['c' => $post['c'], 'r' => $post['r']]);
                        $cell->color = $otherplayer['color'];
                        $cell->owner = $otherplayer['id'];
                        $cell->save();
                        $game = new Game();
                        $game->scoreUp();

                        $this->giveResponse(200, ["data" => "Success, Cell updated"]);
                    }
                }else {
                    $this->giveResponse(404, ["fail" => "R is incorrect size - 0 to 50"]);
                }
            } else {
                $this->giveResponse(404, ["fail" => "C is incorrect size - 0 to 50"]);
            }
        }
        $this->giveResponse(404, ["fail" => "No post information"]);
    }

    protected function giveResponse($code, $data, $content_type = 'application/json')
    {
        $headers = Yii::$app->response->headers;

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $headers->add('Access-Control-Allow-Origin', '*');
            $headers->add('Access-Control-Request-Method', 'POST, GET, DELETE, PUT, PATCH, OPTIONS');
            $headers->add('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token , Authorization');
            $headers->add('Access-Control-Max-Age', '1728000');
            $headers->add('Content-Length', '0');
            $headers->add('Content-Type', 'text/plain');
            die();
        }

        $headers->add('Content-Type', $content_type . '; charset=UTF-8');
        $headers->add('Access-Control-Allow-Origin', '*');
        $headers->add('Access-Control-Request-Method', 'POST, GET, DELETE, PUT, PATCH, OPTIONS');

        $response = Yii::$app->response;
        $response->statusCode = $code;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;

        $response->send();
        die();
    }
}