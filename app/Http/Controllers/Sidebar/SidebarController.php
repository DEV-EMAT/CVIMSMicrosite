<?php

namespace App\Http\Controllers\Sidebar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Sidebar\SidebarNav;
use App\Sidebar\NavbarItem;
use DB;


class SidebarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    public function findAll(){

        $sidebarData = SidebarNav::all();
        $arrNavItem = array();
        $resultData = array();

        foreach($sidebarData as $value){
            $arrNavItem = array();
            $arrNavItem = NavbarItem::where('sidebar_id', $value->id)->get();
            $finalResultData = array(
                'id'=> $value->id,
                'route'=> $value->route,
                'font_icon'=> $value->font_icon,
                'title'=> $value->title,
                'collapse_href'=> $value->collapse_href,
                'navbarItem' => $arrNavItem
            );
            $resultData[] = $finalResultData;
        }
        return json_encode($resultData);

    }
}
