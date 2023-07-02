<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dictionary;

class DictionaryController extends Controller
{
    function list()
    {
        $user_id = Auth::id();

        $list = Dictionary::where("user_id", $user_id)->select("id", "title", "tags")->orderBy('created_at', 'DESC')->paginate(12);

        for ($i = 0; $i < count($list); $i++) {
            $list[$i]->tags = explode(",", $list[$i]->tags);
        }

        return response()->json($list);
    }

    function create(Request $request)
    {
        $tag_list = explode(",", $request->tags);
        $request->merge(["tags" => $tag_list]);

        $request->validate([
            'title' => 'required|max:30',
            'tags' => 'required|array|max:20',
            'explain' => 'required|max:1000'
        ]);

        $user_id = Auth::id();
        $title = $request->title;
        $tags = implode(",", $request->tags);
        $explain = $request->explain;

        $page = Dictionary::create([
            'user_id' => $user_id,
            'title' => $title,
            'tags' => $tags,
            'explain' => $explain,
        ]);

        return response()->json($page->id);
    }

    function update(Request $request)
    {
        $tag_list = explode(",", $request->tags);
        $request->merge(["tags" => $tag_list]);

        $request->validate([
            'dictionary_id' => 'required|integer',
            'title' => 'required|max:30',
            'tags' => 'required|array|max:20',
            'explain' => 'required|max:1000'
        ]);

        $user_id = Auth::id();
        $dictionary_id = $request->dictionary_id;
        $title = $request->title;
        $tags = implode(",", $request->tags);
        $explain = $request->explain;

        $dictionary = Dictionary::where("id", "=", $dictionary_id)->where("user_id", $user_id)->first();


        $dictionary->title = $title;
        $dictionary->tags = $tags;
        $dictionary->explain = $explain;
        $dictionary->save();

        $dictionary->tags = explode(",", $dictionary->tags);

        return response()->json($dictionary);
    }

    function delete(Request $request)
    {
        $request->validate([
            'dictionary_id' => 'required|integer',
        ]);
        $user_id = Auth::id();
        $dictionary_id = $request->dictionary_id;

        Dictionary::where("id", "=", $dictionary_id)->where("user_id", $user_id)->delete();

        return response()->json("", 200);
    }

    function view($id)
    {
        $user_id = Auth::id();
        $dictionary = Dictionary::where("id", $id)->where("user_id", $user_id)->select("id", "title", "tags", "explain", "created_at", "updated_at")->first();

        $dictionary->tags = explode(",", $dictionary->tags);

        return response()->json($dictionary);
    }

    function tag($word)
    {
        $user_id = Auth::id();
        $dictionary = Dictionary::where("user_id", $user_id)->where("tags", $word)->orWhere('tags', 'LIKE', $word . '%,%')->orWhere('tags', 'LIKE', '%,%' . $word)->orWhere('tags', 'LIKE', '%,%' . $word . '%,%')->select("id", "title", "tags")->orderBy("created_at", "DESC")->paginate(12);

        for ($i = 0; $i < count($dictionary); $i++) {
            $dictionary[$i]->tags = explode(",", $dictionary[$i]->tags);
        }


        return response()->json($dictionary);
    }
}
