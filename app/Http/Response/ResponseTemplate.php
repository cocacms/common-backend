<?php
namespace App\Http\Response;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

class ResponseTemplate implements Responsable
{
    private $code;
    private $data;
    private $message;
    private $paginate;
    private $status = 200;
    private $headers = [];
    public function __construct($code,$data,$message)
    {
        $paginate = null;
        if ($data instanceof LengthAwarePaginator){
            $paginate = $data->toArray();
            $data = $paginate['data'];
            unset($paginate['data']);
        }
        $this->code = $code;
        $this->data = $data;
        $this->paginate = $paginate;
        $this->message = $message;
    }

    public function status($status)
    {
        $this->status = $status;
        return $this;
    }

    public function header($key,$value){
        array_push($this->headers,[$key => $value]);
        return $this;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request = null)
    {
        if (is_null($request)) $request = app('request');
        if ($request->acceptsJson()){
            return response()
                ->json(
                    [
                        'code' => $this->code,
                        'data' => $this->data,
                        'paginate' => $this->paginate,
                        'message' => $this->message

                    ]
                )
                ->withHeaders($this->headers)
                ->setStatusCode($this->status)
                ;
        }else{
            dd(
                [
                    'code' => $this->code,
                    'data' => $this->data,
                    'paginate' => $this->paginate,
                    'message' => $this->message
                ]
            );
        }
    }
}