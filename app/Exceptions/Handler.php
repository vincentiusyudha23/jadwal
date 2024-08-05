<?php

namespace App\Exceptions;

use Throwable;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Throwable $e)
    // {
    //     if($e instanceof UnauthorizedException){
    //         return redirect()->route('first_page')->with('failed', 'Anda Tidak Punya Akses ke Halaman ini.');
    //     }

    //     return parent::render($request, $e);
    // }
}
