<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Modules;
use App\CategoryLarges;
use DB;

class HomeController extends Controller
{
    protected static $_alias = 'talk';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $getUsers = Users::all();
        $getUsers = Users::where('id', 6)->first();
        $getModules = Modules::where('alias', self::$_alias)->first();
        $getCategoryLarges = CategoryLarges::where('module_id', $getModules->id)->orderBy('sort_order','asc')->get();
        $talks = DB::table('cs_entry')
                    ->join('cs_user', 'cs_user.id', '=', 'cs_entry.user_id')
                    ->select('cs_entry.*', 'cs_user.profile_img', 'cs_user.nickname')
                    ->whereNull('cs_entry.deleted')
                    ->whereIn('cs_entry.status', [1, 2])
                    ->where('cs_entry.module_id', 1)
                    ->orderBy('cs_entry.num_comment', 'desc')
                    ->offset(10)
                    ->limit(10)
                    ->toSql();
        $chienva = DB::table('cs_entry')->toSql();
        dd($talks);
                    die;
        // var_dump($getCategoryLarges);die;
        return view('home', compact('getCategoryLarges'));
    }

}