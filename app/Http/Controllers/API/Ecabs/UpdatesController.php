<?php

namespace App\Http\Controllers\API\Ecabs;

use App\Http\Controllers\Controller;
use App\Ecabs\Update;
use Storage;
use Auth;
use DB;

class UpdatesController extends Controller
{
    //
    public $successStatus = 200;
    public $successCreateStatus = 201;
    public $errorStatus = 404;
    public $queryErrorStatus = 400;

    public function getAllUpdates()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                if(Auth::user()->account_status == 1){
                    $updates = Update::leftJoin('update_account_departments', 'updates.id', 'update_account_departments.update_id')
                                ->leftJoin('departments', 'update_account_departments.merging_dept_id', 'departments.id')
                                ->select(
                                    'departments.department',
                                    'departments.logo',
                                    'updates.id',
                                    'updates.category',
                                    'updates.title',
                                    'updates.content_path',
                                    'updates.images_path',
                                    'updates.created_at'
                                )->where('updates.status', '=', 1)->latest()->paginate(10);

                    foreach ($updates as $update)
                    {
                        $update->content_path = Storage::disk('local')->get('public/ecabs/updates/'. $update->content_path);
                        $update->images_path = unserialize($update->images_path);

                        if(strlen($update->content_path) > strlen(html_cut($update->content_path,500))){
                            $update->see_more = html_cut($update->content_path,500);
                        } else {
                            $update->see_more = null;
                        }
                    }

                    DB::commit();

                    return response()->json(['success' => $updates ], $this->successStatus);
                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus], $this->errorStatus);
                }

            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function getUpdate($id)
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                if(Auth::user()->account_status == 1){
                    $updates = Update::leftJoin('update_account_departments', 'updates.id', 'update_account_departments.update_id')
                                ->leftJoin('departments', 'update_account_departments.merging_dept_id', 'departments.id')
                                ->select(
                                    'departments.department',
                                    'departments.logo',
                                    'updates.id',
                                    'updates.category',
                                    'updates.title',
                                    'updates.content_path',
                                    'updates.images_path',
                                    'updates.created_at'
                                )->where('updates.id', '=', $id)->where('updates.status', '=', 1)->first();


                    $updates->content_path = Storage::disk('local')->get('public/ecabs/updates/'. $updates->content_path);
                    $updates->images_path = unserialize($updates->images_path);

                    if(strlen($updates->content_path) > strlen(html_cut($updates->content_path,500))){
                        $updates->see_more = html_cut($updates->content_path,500);
                    } else {
                        $updates->see_more = null;
                    }

                    DB::commit();

                    return response()->json(['success' => $updates], $this->successStatus);
                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus], $this->errorStatus);
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }

    public function getAllRecentFromDepartment()
    {
        if(Auth::user()->account_status == 1){
            try {
                DB::beginTransaction();
                if(Auth::user()->account_status == 1){
                    $updates = Update::leftJoin('update_account_departments', 'updates.id', 'update_account_departments.update_id')
                                ->leftJoin('departments', 'update_account_departments.merging_dept_id', 'departments.id')
                                ->select(
                                    'departments.id',
                                    'departments.department',
                                    'departments.logo',
                                    'updates.id AS update_id',
                                    'updates.category',
                                    'updates.title',
                                    'updates.content_path',
                                    'updates.created_at'
                                )->where('updates.status', '=', 1)->orderby('updates.created_at', 'desc')->get();

                    foreach ($updates as $update)
                    {
                        $update->content_path = Storage::disk('local')->get('public/ecabs/updates/'. $update->content_path);
                    }

                    $match = [];
                    $final_updates = [];
                    foreach($updates as $key => $value)
                    {
                        if(!in_array($value->id, $match))
                        {
                            $match[] = $value->id;
                            $final_updates[] = $updates[$key];
                            continue;
                        }
                        unset($updates[$key]);
                    }
                    DB::commit();

                    return response()->json(['success' => $final_updates], $this->successStatus);

                } else {
                    DB::commit();
                    return response()->json(['error' => $this->errorStatus], $this->errorStatus);
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                return response()->json($e, $this->queryErrorStatus);
            }
        } else {
            return response()->json(['error' => $this->errorStatus], $this->errorStatus);
        }
    }
}
