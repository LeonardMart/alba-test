<?php
namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        return response()->json(Bookmark::with(['user', 'post'])->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        $bookmark = Bookmark::create($request->all());
        return response()->json($bookmark, 201);
    }

    public function destroy(Request $request, Bookmark $bookmark)
    {
        logger()->info('request: ' . $request);
        logger()->info('Menerima request untuk menghapus bookmark ID: ' . $bookmark);
        $bookmark->delete();
        return response()->json($bookmark, 200);
    }
}
