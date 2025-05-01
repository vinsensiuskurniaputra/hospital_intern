<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stase;
use Illuminate\Http\Request;

class AdminStaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.stase.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Stase $stase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stase $stase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stase $stase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stase $stase)
    {
        //
    }
}
