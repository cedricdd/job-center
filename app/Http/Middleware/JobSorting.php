<?php

namespace App\Http\Middleware;

use Closure;
use App\Constants;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobSorting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jobSorting = session("sort");

        //Invalid sorting, switch to default
        if (!array_key_exists($jobSorting, Constants::JOB_SORTING)) {
            session(["sort" => Constants::JOB_SORTING_DEFAULT]);

            $jobSorting = session("sort", Constants::JOB_SORTING_DEFAULT);
        }

        //We add the sorting as a route parameter to have it easily accessible in the controllers
        $request->route()->setParameter('jobSorting', $jobSorting);

        return $next($request);
    }
}
