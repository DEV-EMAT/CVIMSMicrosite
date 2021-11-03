<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function covid_tracer()
    {
        return view('covidtracer.dashboard', ['title' => "Covid-19 Dashboard"]);
    }

    public function vaccine_dashboard()
    {
        return view('covid19_vaccine.statistics.dashboard', ['title' => "Covid-19 Immunization Dashboard"]);
    }

    public function tv_dashboard()
    {
        return view('covidtracer/tv_dashboard.epidemiology');
    }

    public function tv_dashboard_phil()
    {
        return view('covidtracer/tv_dashboard.phil_epidemiology');
    }


    public function tv_dashboard_stats()
    {
        return view('covidtracer/tv_dashboard.epidemiology_stats');
    }

    public function ecabs()
    {
        return view('ecabs.dashboard');
    }
    
    public function iskocab()
    {
        return view('iskocab.dashboard.index',  ['title' => "CYDAO Dashboard"]);
    }
}
