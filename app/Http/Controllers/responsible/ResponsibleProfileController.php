<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResponsibleUser;
use Auth;

class ResponsibleProfileController extends Controller
{
    /**
     * Display the profile page for responsible user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $responsible = ResponsibleUser::where('user_id', $user->id)->first();
        
        return view('pages.responsible.profile.index', compact('user', 'responsible'));
    }
}