<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Http\Resources\BookResource;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return BookResource::collection($books);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'publisher'      => 'required|string|max:255',
            'published_year' => 'required|integer', 
            'description'    => 'nullable|string',
            'is_available'   => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Simpan ke Database
        // Perhatikan nama key sebelah kiri harus sama dengan kolom database
        $book = Book::create([
            'title'          => $request->title,
            'author'         => $request->author,
            'publisher'      => $request->publisher,
            'published_year' => $request->published_year, // Jangan pakai 'year' lagi
            'description'    => $request->description,
            'is_available'   => $request->is_available ?? true,
        ]);

        return response()->json([
            'message' => 'Book created successfully',
            'data'    => new BookResource($book)
        ], 201);
    }

    public function show(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return new BookResource($book);
    }

    public function update(Request $request, string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        // Validasi Update
        $validator = Validator::make($request->all(), [
            'title'          => 'sometimes|required|string|max:255',
            'author'         => 'sometimes|required|string|max:255',
            'publisher'      => 'nullable|string|max:255',
            'published_year' => 'sometimes|required|integer', // Sesuaikan
            'description'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update data
        $book->update($request->all());

        return response()->json([
            'message' => 'Book updated successfully',
            'data'    => new BookResource($book)
        ], 200);
    }

    public function destroy(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully'
        ], 200);
    }

    // Fitur tambahan Borrow/Return
    public function borrowReturn(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->is_available = !$book->is_available;
        $book->save();

        $statusMessage = $book->is_available ? 'Book returned successfully' : 'Book borrowed successfully';

        return response()->json([
            'message' => $statusMessage,
            'data'    => new BookResource($book)
        ], 200);
    }
}