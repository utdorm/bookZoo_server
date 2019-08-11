<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Model\RentRequest;
use App\Model\Book;
use App\Model\BookTag;
use App\Model\Tag;

class BookController extends Controller
{
    public function index() {
        $books = Book::withTrashed()
            ->with('bookTags.tag')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($books);
    }
    public function view($id){
        $book = Book::withTrashed()
            ->with('bookTags.tag')
            ->findOrFail($id);
        $rentRequest = RentRequest::where('book_id', $id)->orderBy('created_at', 'desc')->first();
        if($book->status === 'renting'){
            $book->rentRequest = $rentRequest;
        }
        return response()->json($book);
    }
    public function available() {
        $books = Book::where('status', 'available')
            ->with('bookTags.tag')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($books);
    }
    public function rented() {
        $books = Book::where('status', 'renting')
            ->with('bookTags.tag')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($books);
    }
    public function deleted() {
        $books = Book::onlyTrashed()
            ->with('bookTags.tag')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($books);
    }
    public function getTrendingBooks() {
        $books = Book::with('bookTags.tag')
            ->where('isTrending', 'yes')
            ->where('status', 'available')
            ->get();
        return response()->json($books);
    }
    public function getNewArrivalBooks() {
        $books = Book::with('bookTags.tag')
            ->where('isNewArrival', 'yes')
            ->where('status', 'available')
            ->get();
        return response()->json($books);
    }
    public function getFromAuthor(BookRequest $request) {
        $author = $request->author;
        $books = Book::with('bookTags.tag')
            ->where('author', $author)
            ->where('status', 'available')
            ->get();
        return response()->json($books);
    }
    public function getByTag (BookRequest $request) {
        $tag = Tag::where('tag_name', $request->tag)->first();
        $books = Book::with('bookTags.tag')
            ->whereHas('bookTags', function($query) use ($tag) {
                $query->where('book_tags.tag_id', $tag->id);
            })
            ->where('status', 'available')
            ->get();
        return response()->json($books);
    }
    public function store(BookRequest $request){
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->summary = $request->summary;
        $book->status = 'available';
        $book->isTrending = $request->isTrending;
        $book->isNewArrival = true;
        $book->price = $request->price;
        $book->rentingPrice = $request->rentingPrice;
        $book->condition = $request->condition;
        $book->image = $request->image;
        // if($request->hasFile('image')){
        //     $path = $request->file('image')->store('books');
        //     $book->image = $path;
        // } else {
        //     $book->image = null;
        // }
        $book->save();
        foreach ($request->tags as $tag) {
            $book->bookTags()->create([
                'tag_id' => Tag::where('tag_name', $tag)->first()->id
            ]);
        }
        return response()->json(Book::with('bookTags.tag')->find($book->id), 200);
    }
    public function uploadImage(BookRequest $request) {
        if($request->hasFile('image')){
            $path = $request->file('image')->store('public/books');
            $path = str_replace('public', 'storage', $path);
            return response()->json($path, 200);
        } 
        return resposne()->json('Unable To upload book');
    }
    public function deleteBook($id) {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json();
    }
    public function restoreBook($id) {
        $book = Book::withTrashed()
            ->with('bookTags.tag')
            ->find($id);
        $book->restore();

        return response()->json($book);
    }
    public function perminantDelete($id) {
        $book = Book::withTrashed()->find($id);
        $book->forceDelete();
        return response()->json();
    }
    public function searchByTitle(BookRequest $request){
        $books = Book::withTrashed()
            ->with('bookTags.tag')
            ->where('title', 'like', $request->keyword.'%')
            ->get();
        return response()->json($books);
    }

    public function editTitle(BookRequest $request, $id){
        $book = Book::withTrashed()->find($id);
        $book->title = $request->title;
        $book->save();
        return response()->json();
    }
    public function editAuthor(BookRequest $request, $id){
        $book = Book::withTrashed()->find($id);
        $book->author = $request->author;
        $book->save();
        return response()->json();
    }
    public function editSummary(BookRequest $request, $id){
        $book = Book::withTrashed()->find($id);
        $book->summary = $request->summary;
        $book->save();
        return response()->json();
    }
    public function editTrendiness(BookRequest $request, $id){
        $book = Book::withTrashed()->find($id);
        $book->isTrending = $request->isTrending;
        $book->isNewArrival = $request->isNewArrival;
        $book->save();
        return response()->json();
    }
    public function editCondition(BookRequest $request, $id){
        $book = Book::withTrashed()->find($id);
        $book->condition = $request->condition;
        $book->save();
        return response()->json();
    } 
    public function editPrice(BookRequest $request, $id){
        $book = Book::withTrashed()->find($id);
        $book->price = $request->price;
        $book->rentingPrice = $request->rentingPrice;
        $book->save();
        return response()->json();
    } 

    public function getAllTags(){
        $tags = Tag::all();
        return response()->json($tags);
    }
    public function deleteBookTags(BookRequest $request, $id){
        $tags = $request->tags;
        foreach($tags as $tag){
            $bookTags = BookTag::where('book_id', $id)
                ->where('tag_id', Tag::where('tag_name', $tag)->first()->id)
                ->delete();
        }
        $remainTags = Book::withTrashed()->with('bookTags.tag')->find($id);
        return response()->json($remainTags);
    }
    public function addBookTags(BookRequest $request, $id){
        $book = Book::withTrashed()->find($id);
        $tags = $request->tags;
        foreach($tags as $tag) {
            $book->bookTags()->create([
                'tag_id' => Tag::where('tag_name', $tag)->first()->id
            ]);
        }
        return response()->json(Book::withTrashed()->with('bookTags.tag')->find($id));
    }
}
