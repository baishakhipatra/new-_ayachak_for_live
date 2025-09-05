<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        Menu::create([
            'title' => $request->title,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'order' => 0,
        ]);

        return redirect()->back()->with('success', 'Menu added successfully.');
    }

    public function updateOrder(Request $request)
    {
        $this->saveOrder($request->menu);
        return response()->json(['success' => true]);
    }

    private function saveOrder($items, $parentId = null)
    {
        foreach ($items as $index => $item) {
            Menu::where('id', $item['id'])->update([
                'order' => $index,
                'parent_id' => $parentId
            ]);

            if (isset($item['children'])) {
                $this->saveOrder($item['children'], $item['id']);
            }
        }
    }
}
