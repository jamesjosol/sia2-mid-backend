<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller {
    
    public function index() {
        $books = Book::orderBy('title')->get();
        return response()->json($books, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'title'             => 'required',
            'genre'             => 'required',
            'author'            => 'required',
            'description'       => 'required',
            'pub_date'          => 'required|date',
        ]);

        try {
            $book = Book::create($request->all());
            return response()->json($book, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function show(Book $book) {
        return response()->json($book, 200);
    }

    public function update(Request $request, Book $book) {
        try {
            $book->update($request->all());
            return response()->json($book, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function destroy(Book $book) {
        $book->delete();
        return response()->json(['message' => 'Book deleted.'], 202);
    }
}
