<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ดึงค่าจาก Session หรือใช้ Default จาก Config
        $locale = Session::get('locale', config('app.locale'));

        // ตรวจสอบว่ามีคีย์ภาษานี้อยู่ใน config/app.php หรืออาเรย์ที่เรากำหนดไว้หรือไม่
        if (array_key_exists($locale, config('languages', []))) {
            App::setLocale($locale);
        }
        return $next($request);
    }
}
