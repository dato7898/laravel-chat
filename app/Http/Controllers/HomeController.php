<?php

namespace App\Http\Controllers;

use Auth;
use App\Status;

class HomeController extends Controller {
    public function index() {
        if (Auth::check()) {
            // Method lists() is deprecated in Laravel v5.2^ 
            // It is renamed to: pluck()
            // https://stackoverflow.com/questions/40280363/method-lists-does-not-exist
            $statuses = Status::notReply()->where(function($query) {
                return $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('user_id', Auth::user()->friends()->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            return view('timeline.index')
                ->with('statuses', $statuses);
        }

        return view('home');
    }
}

?>
