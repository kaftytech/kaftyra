<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function appSetting()
    {
        return view('settings.app.index');
    }

    public function prefixSetting()
    {
        return view('settings.prefix.index');
    }
}
