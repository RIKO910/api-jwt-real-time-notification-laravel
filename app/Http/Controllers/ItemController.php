<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // Create a new item (POST /api/items)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $item = Item::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return response()->json($item, 201);
    }

    // Get all user's items (GET /api/items)
    public function index()
    {
        $items = Auth::user()->items;
        return response()->json($items);
    }

    // Update an item (PUT /api/items/{id})
    public function update(Request $request, $id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);
        $item->update($request->all());

        return response()->json($item);
    }

    // Delete an item (DELETE /api/items/{id})
    public function destroy($id)
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}

