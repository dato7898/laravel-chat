<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller {
    public function getResults(Request $request) {
        $query = $request->input('query');

        if (!$query) {
            abort(404);
        }

        $users = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->get();

        return view('search.results')->with('users', $users);
    }
}

?>
