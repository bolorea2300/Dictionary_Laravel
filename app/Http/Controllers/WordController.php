<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dictionary;
use App\Models\Word;

class WordController extends Controller
{
    function create(Request $request)
    {
        $request->validate([
            'dictionary_id' => 'required|integer',
            'title' => 'required|max:30',
            'explain' => 'required|max:1000'
        ]);

        $user_id = Auth::id();
        $dictionary_id = $request->dictionary_id;
        $title = $request->title;
        $explain = $request->explain;

        $count = Dictionary::where("id", $dictionary_id)->where("user_id", $user_id)->count();

        if ($count === 1) {
            Word::create([
                'user_id' => $user_id,
                'dictionary_id' => $dictionary_id,
                'title' => $title,
                'explain' => $explain,
            ]);

            $list = $this->database($user_id, $dictionary_id);
            return response()->json($list);
        }
    }

    function update(Request $request)
    {
        $request->validate([
            'dictionary_id' => 'required|integer',
            'word_id' => 'required|integer',
            'title' => 'required|max:30',
            'explain' => 'required|max:1000'
        ]);

        $user_id = Auth::id();
        $dictionary_id = $request->dictionary_id;
        $word_id = $request->word_id;
        $title = $request->title;
        $explain = $request->explain;

        $word = Word::where("id", $word_id)->where("dictionary_id", $dictionary_id)->where("user_id", $user_id)->first();

        if (isset($word)) {
            $word->title = $title;
            $word->explain = $explain;
            $word->save();

            $list = $this->database($user_id, $dictionary_id);
            return response()->json($list);
        } else {
            return response()->json("", 500);
        }
    }

    function delete(Request $request)
    {
        $request->validate([
            'dictionary_id' => 'required|integer',
            'word_id' => 'required|integer',
        ]);

        $user_id = Auth::id();
        $dictionary_id = $request->dictionary_id;
        $word_id = $request->word_id;

        $word = Word::where("id", $word_id)->where("dictionary_id", $dictionary_id)->where("user_id", $user_id)->delete();

        $list = $this->database($user_id, $dictionary_id);
        return response()->json($list);
    }

    function list($id)
    {
        $user_id = Auth::id();
        $dictionary_id = $id;

        $list = $this->database($user_id, $dictionary_id);
        return response()->json($list);
    }

    function database($user, $dictionary)
    {
        $list = Word::where("user_id", $user)->where("dictionary_id", $dictionary)->select("id", "title", "explain", "updated_at")->orderBy('created_at', 'ASC')->get();
        return $list;
    }
}
