<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireDateAndSectionInUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Use consistent parameter names
        $date = $request->query('get_date');
        $section = $request->query('get_am_pm');

        // Store to session if present in URL
        if ($date && $section) {
            session([
                'selected_date' => $date,
                'selected_section' => $section,
            ]);
        }

        // Check if already in session
        if (!session('selected_date') || !session('selected_section')) {
            return redirect()->route('select.date.section');
        }

        return $next($request);
    }
}
