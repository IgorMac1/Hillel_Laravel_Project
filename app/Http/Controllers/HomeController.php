<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contract\FileStorageServiceContract;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FileStorageServiceContract $contract)
    {
        $this->contract = $contract;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd($this->contract->generate());
        return view('home');
    }
}
