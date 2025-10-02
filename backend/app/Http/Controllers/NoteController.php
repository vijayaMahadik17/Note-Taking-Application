<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    public function index()
    {
        return response()->json(Note::orderBy('created_at', 'desc')->get());
    }

    public function show(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        return response()->json($note);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = new Note();
        $note->id = (string) Str::uuid();
        $note->title = $validated['title'];
        $note->content = $validated['content'];
        $note->save();

        return response()->json($note, 201);
    }

    public function update(Request $request, string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note->title = $validated['title'];
        $note->content = $validated['content'];
        $note->save();

        return response()->json($note);
    }

    public function destroy(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        $note->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
