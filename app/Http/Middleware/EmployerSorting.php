<?php

namespace App\Http\Middleware;

use Closure;
use App\Constants;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployerSorting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sorting = session("employer-sorting");

        //Invalid sorting, switch to default
        if(!array_key_exists($sorting, Constants::EMPLOYER_SORTING)) {
            session(["employer-sorting" => Constants::EMPLOYER_SORTING_DEFAULT]);

            $sorting = Constants::EMPLOYER_SORTING_DEFAULT;
        }

        $request->route()->setParameter('employerSorting', $sorting);

        return $next($request);
    }
}
