<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Asm89\Stack\CorsService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */

    //'render' method is executed every time that an exception is thrown.
    public function render($request, Exception $exception)
    {
        //corsミドルウェアがすべてのエラーよりも先んじて実行されるようにする
        $response = $this->handleException($request, $exception);

        app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;
    }
    public function handleException($request, Exception $exception)
    {
        //errorの型がvalidationExceptionの場合のみ適用
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        //modelが存在しないとき
        if ($exception instanceof ModelNotFoundException) {
            //model名も出力
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exists any {$modelName} with the specified identifier", 404);
        }
        //未認証ユーザ
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }
        //未認可
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), 403);
        }
        //エンドポイントはあってるがmethodが違う
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('The specified method for the requests is invalid', 405);
        }
        //undefined endpoint in route
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('The specified URL cannot be found', 404);
        }
        //それ以外のhttp関連エラー
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        //RDBなのでtransactionやproductとrelateされたuserは削除できない
        if ($exception instanceof QueryException) {
            // dd($exception);
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->errorResponse('Cannot remove this resource permanently . It is related with any other resource', 409);
            }
        }
        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input()); //フォームの値を残す
        }
        if (config('app.debug')) { //dev環境の場合はrendering
            //laravelのエラーペーじをrenderingしているメソッドがこれ↓
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected Exception. Try later', 500);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) { //web からのリクエストの場合はloginページへリダイレクト
            return redirect()->guest('login');
        }
        return $this->errorResponse('Unauthenticated.', 401);
    }



    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    //validationエラー時に自動的に404ページに飛ぶ仕様になっているため、API仕様にoverwriteする
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }
    private function isFrontend($request)
    { //frontendからのリクエストかどうかcheck
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
