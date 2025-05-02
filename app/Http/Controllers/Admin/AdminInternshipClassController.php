<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipClass;
use Illuminate\Http\Request;

class AdminInternshipClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.internship_class.index');
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
    public function show(InternshipClass $internshipClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InternshipClass $internshipClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InternshipClass $internshipClass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InternshipClass $internshipClass)
    {
        //
    }
}
