<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::paginate(10);
        return view('pages.admin.menu.index', compact('menus'));
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
            'url' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'order' => 'required|integer',
            'parent_id' => 'nullable|exists:menus,id',
        ]);

        $menus = Menu::create([
            'name' => $validatedData['name'],
            'url' => $validatedData['url'],
            'icon' => $validatedData['icon'],
            'order' => $validatedData['order'],
            'parent_id' => $validatedData['parent_id'],
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menus created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $menu = $menu;
        $menus = Menu::all();
        
        return view('pages.admin.menu.edit', compact('menu', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'order' => 'required|integer|max:255',
            'parent_id' => 'nullable|exists:menus,id',
        ]);
        

        $menu->update([
            'name' => $validatedData['name'],
            'url' => $validatedData['url'],
            'icon' => $validatedData['icon'],
            'order' => intval($validatedData['order']),
            'parent_id' => $validatedData['parent_id'],
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = Menu::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = trim($request->search);
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        $menus = $query->paginate(10);

        return view('components.admin.menu.table', compact('menus'))->render();
    }
}
