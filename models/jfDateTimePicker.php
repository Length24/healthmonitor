<?php

namespace app\models;

class jfDateTimePicker
{
    public static function getDateTimeInput(
        $controlClass,
        $dateName,
        $timeName,
        $dateText = "Send at Date",
        $timeText = "Send at Time",
        $currentDate = null,
        $currentTime = null,
        $futureAllowed = false
    )
    {

        if ($currentDate == null) {
            $date = new \dateTime();
            $currentDate = $date->format('Y-m-d');
            if ($currentTime == null) {
                $currentTime = $date->format('h:m');
            }
        }

        $max = '';
        if ($futureAllowed == false) {
            $max = 'max = ' . $currentDate;
        }

        return '  
                    <div class="col-sm-12 col-md-5">
                        <label for="senddaydate" class="question-group-title control-label">' . $dateText . '</label>
                        <input type="date"
                         id="senddaydate"
                          placeholder="' . $currentDate . '"
                           name="' . $dateName . '" 
                           class = "' . $controlClass . '"
                           ' . $max . '
                        value = "' . $currentDate . '">
                    </div>
                    <div class="col-sm-12 col-md-5">
                        <label for="senddaytime" class="question-group-title control-label">' . $timeText . '</label>
                        <input type="time" 
                        id="senddaytime" 
                        name="' . $timeName . '" 
                        class = "' . $controlClass . '"
                        placeholder="' . $currentTime . '" 
                        min="00:00" 
                        max="23:59" required
                        value = "' . $currentTime . '">
                    </div>
                    
               
               
           ';
    }

    public static function getDateToDateInput($controlClass, $dateNameFrom, $dateNameTo, $dateText = "Start", $endDateText = "End", $from = null, $to = null)
    {
        $today = new \dateTime();
        $today = $today->format('Y-m-d');

        if ($from == null) {
            $from = $today;
        }

        if ($to == null) {
            $to = $today;
        }

        //just to make the ID's unique incase need to add Jscript to this model later.
        $random1 = random_int(1000000, 99999999);
        $random2 = random_int(1000000, 99999999);
        return '  
                    <div class="col-sm-6">
                        <label for="senddaydate">' .  $dateText. '</label>
                        <input type="date"
                         id="senddaydate_' . $random1 . '"
                          placeholder="' . $from . '"
                           name="' . $dateNameFrom . "_1" . '" 
                           class = "' . $controlClass . ' senddaydate"
                        value = "' . $from . '"
                        max = "' . $today . '">
                    </div>
                    <div class="col-sm-6">
                        <label for="senddaydate">' .  $endDateText. '</label>
                        <input type="date"
                         id="senddaydate_' . $random2 . '"
                          placeholder="' . $to . '"
                           name="' . $dateNameTo . "_2" . '" 
                           class = "' . $controlClass . ' senddaydate"
                        value = "' . $to . '"
                         max = "' . $today . '">
                    </div>
                   
               
           ';
    }
}
