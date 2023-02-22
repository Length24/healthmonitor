<?php
/**
 * Created by PhpStorm.
 * User: jfearnley
 * Date: 13/09/2022
 * Time: 10:21
 */
namespace app\modules\bp\models;

use app\models\readyplayer1\ReadyPlayer1Lookup;
use app\models\readyplayer1\ReadyPlayer1Scores;
use Yii;

class bpmodel extends \yii\base\Model
{
    public function scoreUp()
    {
        $cookies = Yii::$app->request->cookies;
        $owner = null;
        if (isset($cookies['ownerId'])) {
            $owner = $cookies['ownerId']->value;
        }
        $eve = [];
        $events = ReadyPlayer1Lookup::find()->all();
        foreach ($events as $event) {
            $eve[$event->eventid]['desc'] = $event->description;
            $eve[$event->eventid]['score'] = $event->score;
        }


        //check first corner aware
        $sqlEvent1 = "SELECT `owner`
              FROM (
              SELECT c, r, rpb.`owner`, rps.`event`, count(rpb.`owner`) as CornerCount
              FROM engwiki.readyplayer1board rpb
              LEFT JOIN engwiki.readyplayerscores rps ON rpb.`owner` = rps.player AND rps.`event` = 1
              WHERE ((rpb.c = 0 AND rpb.r = 0) OR (rpb.c = 0 AND rpb.r = 50) OR (rpb.c = 50 AND rpb.r = 50) OR (rpb.c = 50 AND rpb.r = 0)) AND ISNULL(rps.`event`)
              GROUP BY rpb.`owner`
              ) a
              WHERE a.CornerCount > 3";

        $result = Yii::$app->db->createCommand($sqlEvent1)->queryOne();
        if($result != false && $result['owner'] == $owner) {
            $score = new ReadyPlayer1Scores();
            $score->event = 1;
            $score->player = $owner;
            $score->score = $eve[1]['score'];
            $score->save();
        }

        //check all squares
        $sqlEvent2 = "SELECT `owner`
            FROM (
            SELECT c, r, rpb.`owner`, rps.`event`, count(rpb.`owner`) as CornerCount
            FROM engwiki.readyplayer1board rpb
            LEFT JOIN engwiki.readyplayerscores rps ON rpb.`owner` = rps.player AND rps.`event` = 2
            WHERE ISNULL(rps.`event`)
            GROUP BY rpb.`owner`
            ) a
            WHERE a.CornerCount > 2600;";

        $result = Yii::$app->db->createCommand($sqlEvent2)->queryOne();
        if($result != false && $result['owner'] == $owner) {
            $score = new ReadyPlayer1Scores();
            $score->event = 2;
            $score->player = $owner;
            $score->score = $eve[2]['score'];
            $score->save();
        }

        //check all edges
        $sqlEvent2 = "SELECT `owner`
                        FROM (
                        SELECT c, r, rpb.`owner`, rps.`event`, count(rpb.`owner`) as CornerCount
                        FROM engwiki.readyplayer1board rpb
                        LEFT JOIN engwiki.readyplayerscores rps ON rpb.`owner` = rps.player AND rps.`event` = 3
                        WHERE (rpb.c = 0 OR rpb.c = 50 OR rpb.r = 50 OR  rpb.r = 0) AND ISNULL(rps.`event`)
                        GROUP BY rpb.`owner`
							) a
                        WHERE a.CornerCount > 199;
                        ";

        $result = Yii::$app->db->createCommand($sqlEvent2)->queryOne();
        if($result != false && $result['owner'] == $owner) {
            $score = new ReadyPlayer1Scores();
            $score->event = 3;
            $score->player = $owner;
            $score->score = $eve[3]['score'];
            $score->save();
        }

    }

    public function getScores() {
        $sql = "SELECT *
                  FROM ( 
                    SELECT rp.username, SUM(rps.score) as score, rp.color, rp.id
                    FROM engwiki.readyplayer rp 
                    INNER JOIN engwiki.readyplayerscores rps ON rps.player = rp.id
                    GROUP BY rp.username
                  ) a
                  ORDER BY a.score DESC";

        return Yii::$app->db->createCommand($sql)->queryAll();

    }


    public function getScorebreakdown() {
        $sql = "SELECT Concat('(',rps.`timestamp`, ')   - ',rp.username,' received ',rps.score,' points for \"', rpl.description,'\"') as text,  rp.color
FROM engwiki.readyplayer rp 
INNER JOIN engwiki.readyplayerscores rps ON rps.player = rp.id
INNER JOIN engwiki.readyplayerlookup rpl ON rpl.eventid = rps.`event`
ORDER BY rps.`timestamp` DESC";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function giftScore() {
        $post = Yii::$app->request->post();
        if(isset( $post['amount']) && $post['amount'] != '') {
            if(isset( $post['player']) && $post['player'] != '') {
                $cookies = Yii::$app->request->cookies;
                $ownerId = null;

                if (isset($cookies['ownerId'])) {
                    $ownerId = $cookies['ownerId']->value;
                }

                $myScore = 0;
                $scores = $this->getScores();
                foreach ($scores as $score) {
                    if ($ownerId == $score['id']) {
                        $myScore = $score['score'];
                    }
                }

                if($myScore > $post['amount']) {
                    $score = new ReadyPlayer1Scores();
                    $score->event = 9;
                    $score->player = $ownerId;
                    $score->score = "-".$post['amount'];
                    $score->save();


                    $score = new ReadyPlayer1Scores();
                    $score->event = 5;
                    $score->player = $post['player'];
                    $score->score = $post['amount'];
                    $score->save();
                    return true;
                }
            }
        }
        return false;
    }
}