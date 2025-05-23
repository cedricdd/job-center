<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::withCount("jobs")->having("jobs_count", ">", 0)->orderBy("name", "ASC")->get();

        return view("tags.index", compact('tags'));
    }

    public function show(Tag $tag, string $jobSorting): View
    {
        $tag->loadCount("jobs");

        $jobs = $tag->jobs()->with(['tags', 'employer'])->orderByRaw(Constants::JOB_SORTING[$jobSorting]["order"])->paginate(Constants::JOBS_PER_PAGE);

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
