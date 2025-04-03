<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount("jobs")->orderBy("name", "ASC")->get();

        return view("tags.index", compact('tags'));
    }

    public function show(Tag $tag): View
    {
        $tag->loadCount("jobs");

        $jobs = $tag->jobs()->with(['tags', 'employer'])->orderBy("created_at", "DESC")->paginate(10);

        return view("tags.show", compact("tag", 'jobs'));
    }

    public function autocomplete(Request $request): array
    {
        $fixedPart = "";
        $term = trim($request->input("term", ''));

        if (strlen($term) < 2) {
            return [];
        }

        $terms = explode(",", $term);
        $terms = array_map('trim', $terms);

        $tags = Tag::select("id", "name")->where("name", "LIKE", "%" . array_pop($terms) . "%")->get();

        $fixedPart = implode(", ", $terms);
        $results = [];

        foreach ($tags as $tag) {
            $results[] = [
                "label" => $tag->name,
                "value" => ($fixedPart ? ($fixedPart . ", ") : "") . $tag->name,
            ];
        }

        return $results;
    }
}
