<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required'
        ]);

        $book = new Book;
        $book->name = $request->input('name');
        $book->author = $request->input('author');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $fileName = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('/images'), $fileName);
            $path = asset(path: 'image') . '/' . $fileName;
            $book->image = $fileName;
        }

        $book->save();
        return redirect()->back()->with('message', 'Book Image Upload Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return $book;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        printf($id);
        $request->validate([
            'name' => 'required',
            'author' => 'required'
        ]);

        $book = Book::find($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $fileName = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('/images'), $fileName);
            $path = asset(path: 'image') . '/' . $fileName;
            $book->image = $fileName;
        }
        Book::where('id', $id)->update([
            'name' => $request->input('name'),
            'author' => $request->input('author'),
            'image' => $fileName,
        ]);
        return redirect()->back()->with('message', 'Book Image Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response(content: '', status: 204);
    }
}
