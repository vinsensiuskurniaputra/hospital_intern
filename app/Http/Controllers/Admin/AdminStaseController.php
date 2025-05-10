<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Stase;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\ResponsibleUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminStaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stases = Stase::paginate(10);
        $responsibles = User::whereHas('roles', function ($query) {
            $query->where('name', 'pic');
        })->get();
        $departements = Departement::all();
        return view('pages.admin.stase.index', compact('stases', 'responsibles', 'departements'));
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'responsibleUsers' => ['required', 'array'],
            'responsibleUsers.*' => ['exists:responsible_users,id'],
            'departement_id' => 'required|exists:departements,id',
            'detail' => 'required|string|max:255',
        ]);

        $responsibles = ResponsibleUser::whereIn('id', $validatedData['responsibleUsers'])->get();


        $stase = Stase::create([
            'name' => $validatedData['name'],
            'departement_id' => $validatedData['departement_id'],
            'detail' => $validatedData['detail'],
        ]);

        $stase->responsibleUsers()->sync($responsibles);

        return redirect()->route('admin.stases.index')->with('success', 'Stase created successfully');
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
        $responsibles = User::whereHas('roles', function ($query) {
            $query->where('name', 'pic');
        })->get();
        $departements = Departement::all();
        return view('pages.admin.stase.edit', compact('stase', 'responsibles', 'departements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stase $stase)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'responsibleUsers' => ['required', 'array'],
                'responsibleUsers.*' => ['exists:responsible_users,id'],
                'departement_id' => 'required|exists:departements,id',
                'detail' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            // Update stase basic info
            $stase->update([
                'name' => $validatedData['name'],
                'departement_id' => $validatedData['departement_id'],
                'detail' => $validatedData['detail'],
            ]);

            // Update responsible users
            $responsibles = ResponsibleUser::whereIn('id', $validatedData['responsibleUsers'])->get();
            $stase->responsibleUsers()->sync($responsibles);

            DB::commit();

            return redirect()
                ->route('admin.stases.index')
                ->with('success', 'Stase updated successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Failed to update stase: ' . $e->getMessage())
                ->withInput();
        }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stase $stase)
    {
        $stase->delete();
        return redirect()->route('admin.stases.index')->with('success', 'Stase deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = Stase::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')->orWhere('detail', 'like', '%' . $request->search . '%');
        }

        $stases = $query->paginate(10);

        return view('components.admin.stase.table', compact('stases'))->render();
    }
}
