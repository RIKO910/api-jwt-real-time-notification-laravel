<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Get all unapproved items (GET /api/admin/items/unapproved)
    public function getUnapprovedItems()
    {
        $items = Item::where('status', 'pending')->get();
        return response()->json($items);
    }

    // Approve an item (PUT /api/admin/items/{id}/approve)
    public function approveItem($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status' => 'approved']);

        return response()->json(['message' => 'Item approved successfully']);
    }

    // Reject an item (PUT /api/admin/items/{id}/reject)
    public function rejectItem($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status' => 'rejected']);

        return response()->json(['message' => 'Item rejected successfully']);
    }

    // Delete any user's item (DELETE /api/admin/items/{id})
    public function deleteItem($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
