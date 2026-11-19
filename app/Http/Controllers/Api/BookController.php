<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BookController extends Controller
{
    #[OA\Get(
        path: '/books',
        tags: ['Books'],
        summary: 'Menampilkan semua buku',
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil daftar buku')
        ]
    )]
    public function index()
    {
        return response()->json([
            'message' => 'Daftar buku berhasil diambil',
            'data' => Book::latest()->get()
        ]);
    }

    #[OA\Post(
        path: '/books',
        tags: ['Books'],
        summary: 'Menambahkan buku',
        responses: [
            new OA\Response(response: 201, description: 'Buku berhasil ditambahkan'),
            new OA\Response(response: 422, description: 'Validasi gagal')
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'published_year' => 'required|integer|min:1900|max:2026',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $book
        ], 201);
    }

    #[OA\Get(
        path: '/books/{book}',
        tags: ['Books'],
        summary: 'Melihat detail buku',
        parameters: [
            new OA\Parameter(
                name: 'book',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Detail buku berhasil diambil')
        ]
    )]
    public function show(Book $book)
    {
        return response()->json([
            'message' => 'Detail buku berhasil diambil',
            'data' => $book
        ]);
    }

    #[OA\Put(
        path: '/books/{book}',
        tags: ['Books'],
        summary: 'Mengubah data buku',
        parameters: [
            new OA\Parameter(
                name: 'book',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Buku berhasil diperbarui')
        ]
    )]
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'sometimes|required|string|max:20|unique:books,isbn,' . $book->id,
            'published_year' => 'sometimes|required|integer|min:1900|max:2026',
            'stock' => 'sometimes|required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $book
        ]);
    }

    #[OA\Delete(
        path: '/books/{book}',
        tags: ['Books'],
        summary: 'Menghapus buku',
        parameters: [
            new OA\Parameter(
                name: 'book',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Buku berhasil dihapus')
        ]
    )]
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}
