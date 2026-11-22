<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class IsMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!empty(Auth::user()->student)) {
            $examId = $request->route('id') ?? $request->route('exam');
            if ($examId) {
                $exam = \App\Models\Exam\Exam::find($examId);
                if ($exam && max(0, (int) $exam->price) === 0) {
                    if (Auth::user()->student->is_member == 0) {
                        Auth::user()->student->update(['is_member' => 1]);
                    }
                    return $next($request);
                }
            }

            if(Auth::user()->student->is_member == 0) return redirect()->back()->with('error', 'Untuk mengakses halaman ini anda harus menjadi member terlebih dahulu.');
        }

        return $next($request);
    }
}
