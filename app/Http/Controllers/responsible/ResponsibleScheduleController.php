<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleScheduleController extends Controller
{
    public function index()
    {
        return view('pages.responsible.schedule.index');
    }

    public function store(Request $request)
    {
        // Logic to store new schedule
    }

    public function update(Request $request, $id)
    {
        // Logic to update schedule
    }

    public function destroy($id)
    {
        // Logic to delete schedule
    }
}