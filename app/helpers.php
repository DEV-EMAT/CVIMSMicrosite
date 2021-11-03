<?php

use Illuminate\Support\Facades\Hash;
use App\Ecabs\Maintenance;
use App\Ecabs\PersonDepartmentPosition;
use App\Ecabs\Person;
use Illuminate\Support\Facades\Storage;

/* ============================================================================================= */
/* ====                                WEB HELPERS                                          ====*/
/* ============================================================================================= */

function convertData($data){
    return htmlspecialchars(mb_strtoupper($data));
}

//define connection
function connectionName($database = "mysql"){
    return Config::get('database.connections.'.$database.'.database');
}

function action_log($module = '', $action = '', $changes = ''){
    $id ='';
    // dd(count($changes));
    // for($index = 0; $index < count($changes); $index++){
    //     // echo $changes[$index];
    //     foreach($changes[$index] as $value){
    //         echo $changes[$index] . ", " . $value . ", \n";
    //     }
    // }
    // foreach($changes as $change){
    //     echo $change[1];
    // }
    if(\Auth::id()){
        $person = Person::findOrFail(Auth::user()->person_id);
        $fullname = $person->last_name;

        if($person->affiliation){
            $fullname .= " " . $person->affiliation;
        }
        $fullname .= ", " . $person->first_name . " ";

        if($person->middle_name){
            $fullname .= $person->middle_name[0] . ".";
        }
        // $id = 'USER : '.\Auth::id(). '|';
        $id = 'USER : '. strtoupper($fullname) . '|';
    }

    $attemptToWriteText = $id.''. strtoupper($module) .' | '. strtoupper($action) . ' | ' . date("Y-m-d") .' | '.  date("H:i:s") .' | '. \Request::ip() .' | '. json_encode($changes);
    $department = PersonDepartmentPosition::where('person_id', '=', Auth::user()->person_id)->with('department_position', 'department_position.departments')->first();
    // $this->getDepartment(Auth::user())->department_position['department_id']

    \Storage::append('logs/'. 'DEPT-'. str_pad($department->department_position->departments['id'] ,5,"0", STR_PAD_LEFT) . '/' . date("Y-m-d").'.xml', $attemptToWriteText);
}

function format_code($prefix = '', $number){
    return strtoupper($prefix) .'-'. str_pad($number ,5,"0", STR_PAD_LEFT);
}

/* ============================================================================================= */
/* ====                                END WEB HELPERS                                          ====*/
/* ============================================================================================= */





/* ============================================================================================= */
/* ====                                API HELPERS                                          ====*/
/* ============================================================================================= */

function identifierCredentials($device_identifier, $identifier_type, $checking){

    //check if hash is same from user input and device indentifier
    if($checking == 'hash_check'){
        return Hash::check($identifier_type, $device_identifier);
    }

    //create new hash for identifer type (web_account, mobile_account, no_device)
    if($checking == 'create_identifier'){
        return Hash::make($identifier_type);
    }
}

function format_number($data){
    $data = htmlspecialchars($data);
    $data = str_replace(' ', '', $data);
    return $data;
}

function cstmCombination()
{
    $first_number = random_int(1, 9);
    $combination_1 = random_int(0, $first_number);
    $combination_2 = random_int(0, ($first_number - $combination_1));
    $combination_3 = $first_number - ($combination_1 + $combination_2);

    return $first_number . $combination_1 . $combination_2 . $combination_3;
}

function checkMaintenaince($module = null){
    $data = Maintenance::join('platforms', 'maintenances.platform_id', 'platforms.id')
            ->where('platforms.platform_type', '!=', 'web')
            ->orderBy('maintenances.created_at', 'desc')
            ->where('maintenances.status', '=', 1)
            ->where('maintenances.description', '=', $module)
            ->first();

    if(is_null($data)){
        return false;
    } else {
        return true;
    }
}

function html_cut($text, $max_length)
{
    $tags   = array();
    $result = "";
    $is_open   = false;
    $grab_open = false;
    $is_close  = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";
    $i = 0;
    $stripped = 0;
    $stripped_text = strip_tags($text);
    while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
    {
        $symbol  = $text[$i];
        $result .= $symbol;
        switch ($symbol)
        {
        case '<':
                $is_open   = true;
                $grab_open = true;
                break;
        case '"':
            if ($in_double_quotes)
                $in_double_quotes = false;
            else
                $in_double_quotes = true;
            break;
            case "'":
            if ($in_single_quotes)
                $in_single_quotes = false;
            else
                $in_single_quotes = true;
            break;
            case '/':
                if ($is_open && !$in_double_quotes && !$in_single_quotes)
                {
                    $is_close  = true;
                    $is_open   = false;
                    $grab_open = false;
                }
                break;
            case ' ':
                if ($is_open)
                    $grab_open = false;
                else
                    $stripped++;
                break;
            case '>':
                if ($is_open)
                {
                    $is_open   = false;
                    $grab_open = false;
                    array_push($tags, $tag);
                    $tag = "";
                }
                else if ($is_close)
                {
                    $is_close = false;
                    array_pop($tags);
                    $tag = "";
                }
                break;
            default:
                if ($grab_open || $is_close)
                    $tag .= $symbol;
                if (!$is_open && !$is_close)
                    $stripped++;
        }
        $i++;
    }
    while ($tags)
        $result .= "</".array_pop($tags).">";
    return $result;
}


/* ============================================================================================= */
/* ====                                API HELPERS                                          ====*/
/* ============================================================================================= */




/* ============================================================================================= */
/* ====                                MOBILE HELPERS                                          ====*/
/* ============================================================================================= */

function mobile_log($event_code = '', $remarks = '', $time_out = '', $extended_by = '', $created_at = '', $updated_at = ''){

    $attemptToWriteText = $event_code .' | '. $remarks . ' | ' . $time_out .' | '.  $extended_by .' | '. $created_at .' | '. $updated_at;

    Storage::append('logs/MOBILE_EVENT_LOGS/'.  date("Y-m-d").'.xml', $attemptToWriteText);
}

/* ============================================================================================= */
/* ====                                MOBILE HELPERS                                          ====*/
/* ============================================================================================= */
?>
