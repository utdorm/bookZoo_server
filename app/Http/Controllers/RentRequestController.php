<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentRequestRequest;
use Illuminate\Support\Facades\Auth;
use App\Model\Book;
use App\Model\RentRequest;

class RentRequestController extends Controller
{
    //
    public function userStore(RentRequestRequest $request)
    {
        $user = Auth::user();
        $book = Book::find($request->book_id);
        if ($book->status === 'renting') {
            return response()->json('Book is not available', 405);
        }
        $userCurrentRequest = RentRequest::where('user_id', $user->id)->where('status', '!=', 'returned')->get();
        if(count($userCurrentRequest) > 0){
            return response()->json('Return the current book first', 405);
        }
        $rentRequest = new RentRequest();
        $rentRequest->book_id = $request->book_id;
        $rentRequest->user_id = $user->id;
        $rentRequest->renter_name = $user->name;
        $rentRequest->phone_number = $user->phoneNumber;
        $rentRequest->book_name = $book->title;
        $rentRequest->deposit = $book->rentingPrice + $book->price;
        $rentRequest->return_date = date('Y-m-d', strtotime("+1 months", strtotime("NOW")));
        $rentRequest->status = 'pending';
        $rentRequest->save();

        return response()->json();
    }

    public function userCurrentRequest() {
        $user = Auth::user();
        $rentRequests = RentRequest::with('book')
            ->where('user_id', $user->id)
            ->where('status', '!=' ,'returned')
            ->get();
        return response()->json($rentRequests);
    }
    public function userAllRequest() {
        $user = Auth::user();
        $rentRequests = RentRequest::with('book')
            ->where('status', 'returned')
            ->where('user_id', $user->id)
            ->get();
        return response()->json($rentRequests);
    }

    public function adminStore(RentRequestRequest $request)
    {
        $book = Book::find($request->book_id);
        if ($book->status === 'renting') {
            return response()->json('Book is not available', 405);
        }
        $rentRequest = new RentRequest();
        $rentRequest->book_id = $book->id;
        $rentRequest->book_name = $book->title;
        $rentRequest->renter_name = $request->renter_name;
        $rentRequest->phone_number = $request->phone_number;
        $rentRequest->deposit = $request->deposit;
        $rentRequest->return_date = date('Y-m-d', strtotime("+1 months", strtotime("NOW")));
        $rentRequest->status = $request->status;
        if ($request->status == 'renting') {
            $rentRequest->confirmed_at = date('Y-m-d');
            $book->status = 'renting';
            $book->save();
        }
        if ($request->status == 'completed'){
            $rentRequest->returned_at = date('Y-m-d');
        }
        $rentRequest->save();
        return response()->json();
    }

    public function adminPendingRequest()
    {
        $rentRequest = RentRequest::with('book')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();
        return response()->json($rentRequest);
    }

    public function adminConfirmedRequest()
    {
        $rentRequest = RentRequest::with('book')
            ->where('status', 'renting')
            ->where('return_date', '>', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();
        return response()->json($rentRequest);
    }

    public function adminOverdueRequest()
    {
        $rentRequest = RentRequest::with('book')
            ->where('status', 'renting')
            ->where('return_date', '<', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();
        return response()->json($rentRequest);
    }
    public function adminCompletedRequest()
    {
        $rentRequest = RentRequest::with('book')
            ->where('status', 'returned')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();
        return response()->json($rentRequest);
    }

    public function confirmRequest($id)
    {
        $rentRequest = RentRequest::find($id);
        $rentRequest->status = 'renting';
        $rentRequest->confirmed_at = date('Y-m-d');
        $rentRequest->save();
        $book = Book::withTrashed()->find($rentRequest->book_id);
        $book->status = 'renting';
        $book->save();
        return response()->json();
    }

    public function completeRequest($id)
    {
        $rentRequest = RentRequest::find($id);
        $rentRequest->status = 'returned';
        $rentRequest->returned_at = date('Y-m-d');
        $rentRequest->save();
        $book = Book::withTrashed()->find($rentRequest->book_id);
        $book->status = 'available';
        $book->save();
        return response()->json();
    }

    public function removeRequest($id)
    {
        $rentRequest = RentRequest::find($id);
        $rentRequest->delete();
        return response()->json();
    }
    
    public function getRequestByDate(RentRequestRequest $request){
        $date = $request->date;
        $received = RentRequest::whereDate('created_at', $date)
            ->get();
        $confirmed = RentRequest::whereDate('confirmed_at', $date)->get();
        $completed = RentRequest::whereDate('returned_at', $date)->get();
        return response()->json([
            'received' => $received,
            'confirmed' => $confirmed,
            'completed' => $completed
        ]);
    }
    
}
