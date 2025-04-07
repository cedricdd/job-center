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
        $sorting = session("job-sorting");

        //Invalid sorting, switch to default
        if (!array_key_exists($sorting, Constants::JOB_SORTING)) {
            session(["job-sorting" => Constants::JOB_SORTING_DEFAULT]);

            $sorting = Constants::JOB_SORTING_DEFAULT;
        }

        //We add the sorting as a route parameter to have it easily accessible in the controllers
        $request->route()->setParameter('jobSorting', $sorting);

        return $next($request);
    }
}
