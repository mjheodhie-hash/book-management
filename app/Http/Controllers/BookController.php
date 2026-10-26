<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of books (supports searching by title or author).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $books = Book::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                         ->orWhere('author', 'like', "%{$search}%");
        })->get();

        return response()->json($books, 200);
    }

    /**
     * Store a newly created book record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'year_published' => 'required|integer|min:1000|max:' . (date('Y') + 1),
        ]);

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Book created successfully.',
            'data' => $book
        ], 201);
    }

    /**
     * Display a specific book record.
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book does not exist.'
            ], 404);
        }

        return response()->json($book, 200);
    }

    /**
     * Update an existing book record.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book does not exist.'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'year_published' => 'required|integer|min:1000|max:' . (date('Y') + 1),
        ]);

        $book->update($validated);

        return response()->json([
            'message' => 'Book updated successfully.',
            'data' => $book
        ], 200);
    }

    /**
     * Remove a book record permanently.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book does not exist.'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.'
        ], 200);
    }
}