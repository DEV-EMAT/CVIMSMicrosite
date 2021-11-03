<?php

namespace App\Http\Controllers\ecabs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use File;
use Storage;
use App\User;
use App\Ecabs\Person;
use App\Ecabs\Department;


class LogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ecabs.logs.index');
    }

    public function findAllLogs(request $request)
    {   
        $data = [];

        $log_directory = storage_path().'/app/logs/';
        if($request['department'] == 'all'){
            $department = Department::all();

            foreach ($department as $value) {
            
                $folder = format_code('DEPT', $value['id']);
                $log_directory = storage_path().'/app/logs/' . $folder . '/';

                $data = array_merge($data, $this->getDataFromXML($log_directory, $request['date_from']));
            }
        }
        else if($request['department']){
            
            $folder = format_code('DEPT', $request['department']);
            $log_directory = storage_path().'/app/logs/' . $folder . '/';

            $data = $this->getDataFromXML($log_directory, $request['date_from']);
        } 
        
        echo json_encode(array("data" => $data));
    }

    public function getDataFromXML($log_directory, $date_from ){

        
        $data = [];

        //check if directory exists
        if(is_dir($log_directory)){
            $cdir = scandir($log_directory, 0);
    
            //get all the files in folder log_directory
            foreach ($cdir as $key => $value)
            {
                if (!in_array($value,array(".","..")))
                {
                    if (is_dir($log_directory . DIRECTORY_SEPARATOR . $value))
                    {
                        $results[$value] =  ($log_directory . DIRECTORY_SEPARATOR . $value);
                    }
                    else
                    {
                        $results[] = $value;
                    }
                }
            }
    
            if($date_from){
                $results = [];
                $results[] = $date_from . ".xml";
            }
            
            foreach($results as $filename){
                $file = $log_directory . "" . $filename;
    
                //open file
                $isExists = file_exists($file);
    
                if($isExists){
                    $myfile = fopen($file, "r") or die("Unable to open file!");
                    
                    // $path = '../../../../app/logs/' . $filename;
        
                    if($myfile){
                        //get data per line
                        $dataperlane = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($dataperlane as $recorddata) {
                            $splitdata = explode('|', $recorddata);
                            
                            $person = explode('USER :', $splitdata[0]);

                            $nestedData = [];
                            $nestedData['user'] = $person[1];
                            $nestedData['module'] = $splitdata[1];
                            $nestedData['action'] = $splitdata[2];
                            $nestedData['date'] = $splitdata[3];
                            $nestedData['time'] = $splitdata[4];
                            $nestedData['ip'] = $splitdata[5];
                            $nestedData['updates'] = $splitdata[6];
                            $data[] = $nestedData;
                        }
                    }
                    //close file
                    fclose($myfile);
                }
            }
        }

        return $data;
    }

    

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
