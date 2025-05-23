<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use App\Models\ResponsibleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $responsibleUser = ResponsibleUser::where('user_id', $user->id)->first();
        
        return view('pages.responsible.profile.index', compact('user', 'responsibleUser'));
    }
}