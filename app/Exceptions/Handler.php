<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use PHPUnit\Util\InvalidJsonException;
use Throwable;

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

        });
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof InvalidJsonException){
            return new JsonResponse(['error'=>'Invalid JSON format'],400);
        }else if($e instanceof ModelNotFoundException){
            $modelId = $request->route('id');
            $modelName = class_basename($e->getModel());
            return new JsonResponse([
                'error' => 'data with id '.$modelId.' in '.$modelName.' is not found'],404);
        }

        return parent::render($request,$e);
    }
}
