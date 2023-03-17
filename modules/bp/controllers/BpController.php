<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 12/09/2022
 * Time: 12:11
 */

namespace app\modules\bp\controllers;

use app\models\bp\Health;

use app\models\bp\HighCharts;
use app\models\bp\Users;

use app\modules\bp\models\Bp;

use app\modules\bp\models\Excel;
use app\modules\bp\models\Pdf;
use Yii;

use yii\db\Exception;
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

        if (!empty($post) && isset($post['username'])) {
            $this->login();
        }

        return $this->createPage();


    }


    private function createPage($page = '/index', $pageParams = [])
    {
        $hostPage = "/bp/bp/";
        $cookies = Yii::$app->request->cookies;
        $username = null;
        if (isset($cookies['user'])) {
            $username = $cookies['user']->value;
        }

        $health = Health::getWeekStats();

        $header = $this->render('/header', ["message" => $this->headerAlertMessage, "params" => $pageParams, "username" => $username, 'hostPage' => $hostPage]);
        $footer = $this->render('/footer', ["params" => $pageParams, "username" => $username, 'footerParams' => $this->getFooterParams(), 'stats' => $health]);

        return $header . $this->render($page, $pageParams) . $footer;
    }

    private function getFooterParams()
    {
        //identify and recheck checkboxes.
        $get = Yii::$app->request->get();
        $array = ['SYSmmHg' => '', 'DIAmmHg' => '', 'Pulse' => '', 'Steps' => '', 'AverageKm' => '', 'otherInfo' => ''];

        $anyChecked = false;
        foreach ($array as $id => $checkboxes) {
            if (isset($get[$id])) {
                $anyChecked = true;
                $array[$id] = 'checked';
            }
        }
        //check all if all off.
        if (!$anyChecked) {
            $array = ['SYSmmHg' => 'checked', 'DIAmmHg' => 'checked', 'Pulse' => 'checked', 'Steps' => 'checked', 'AverageKm' => 'checked', 'otherInfo' => 'checked'];
        }

        $array['to'] = date('Y-m-d');
        $array['from'] = date('Y-m-d', strtotime('-1 week'));
        if (isset($get['fromdate'])) {
            $array['from'] = $get['fromdate'];
        }
        if (isset($get['todate'])) {
            $array['to'] = $get['todate'];
        }

        for ($x = 1; $x <= 6; $x++) {
            $array['type' . $x] = '';
        }
        $array['type1'] = 'selected';
        if (isset($get['filter']) && isset($array['type' . $get['filter']])) {
            $array['type' . $get['filter']] = 'selected';
        }

        return $array;
    }

    public function actionExports()
    {
        return $this->createPage('/exports', ['showfilter' => true]);
    }

    public function actionReporting()
    {
        $newgraph1 = HighCharts::findOne(['id' => 1]);
        $graph[1] = $newgraph1->getGraph();
        $newgraph2 = HighCharts::findOne(['id' => 2]);
        $graph[2] = $newgraph2->getGraph();
        $newgraph3 = HighCharts::findOne(['id' => 3]);
        $graph[3] = $newgraph3->getGraph();

        return $this->createPage('/reporting', ['showfilter' => true, 'graphs' => $graph]);
    }

    public function actionFaq()
    {

        return $this->createPage('/faq', []);
    }

    public function actionEdit()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        if (isset($post['sys']) && isset($get['id'])) {
            $check = Health::findOne(['id' => $get['id'], 'deleted' => 0]);
            $check->updateCheck($post);
            $this->headerAlertMessage = "Report Updated";
            $this->redirect(array('/bp/bp/edit')); //clear the post and get data
        } else if (isset($get['del']) && isset($get['id'])) {
            $check = Health::findOne(['id' => $get['id'], 'deleted' => 0]);

            if ($check !== null) {
                try {
                    $check->deleted = true;
                    $check->save();
                    $this->headerAlertMessage = "Check Deleted";
                } catch (Exception $e) {
                    $this->headerAlertMessage = "Delete Failed";
                }
            } else {
                $this->headerAlertMessage = "Delete Failed";
            }
        }

        $check = null;
        if (isset($get['edit']) && isset($get['id'])) {
            $check = Health::findOne(['id' => $get['id'], 'deleted' => 0]);
        }

        if ($check !== null) {
            return $this->createPage('/dailyupdate', ['editJob' => $check]);
        } else {
            $model = new Bp();
            $fullData = $model->getBpData(true);
            return $this->createPage('/edit', ['dataset' => $fullData, 'showfilter' => true, 'colInfo' => $model->getDataTableColumns()]);
        }
    }

    public function actionProfile()
    {
        return $this->createPage('/profile', ['showfaq' => true]);

    }

    private function GetData()
    {
        $dataClass = new Bp();
        return $dataClass->getBpData();
    }

    public function actionExcel()
    {
        Excel::createExcel($this->getData());
    }

    public function actionPdf()
    {
        Pdf::createPDF($this->getData());
    }

    public function actionWord()
    {
        $this->redirect(array('/bp/bp/export'));
    }

    public function actionDailyupdate()
    {
        $post = Yii::$app->request->post();

        if (isset($post['sys'])) {
            $health = new Bp();
            if ($health->saveHealthCheck($post)) {
                $this->headerAlertMessage = "Added Reading";
            } else {
                $this->headerAlertMessage = "Save Failed";
            }

        }

        return $this->createPage('/dailyupdate', []);
    }

    public function Login() //this needs moving to the model
    {
        $this->headerAlertMessage = "";
        $post = Yii::$app->request->post();
        $user = Users::findOne(['username' => $post['username']]);
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
                $this->redirect(array('/bp/bp/dailyupdate'));

            } else {
                $this->headerAlertMessage = "Incorrect Password";
            }
        }
    }

    public function actionApidocs()
    {
        return $this->createPage('/apidocs', []);
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


                    $signup = new Users();
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
                    $_SESSION['ownerId'] = $signup->id;
                    $this->headerAlertMessage = "User Has been Created";

                } else {
                    $this->headerAlertMessage = "Password needs to be over 8 letters long";
                }
            }
        } else {
            if (isset($post['newusername'])) {
                $this->headerAlertMessage = "No Username is set";
            }
        }

        return $this->createPage('/signup');
    }


    public function actionLogout()
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->readOnly = false;

        $cookies->remove('user');
        $cookies->remove('id');
        $this->redirect('/bp/bp/');
    }

    private function rand_key()
    {
        return str_pad(dechex(mt_rand(0, 0xFFFFFFFFFF)), 16, '0', STR_PAD_LEFT);
    }

}