<?php

namespace Framework\Response;

class Response {
    //array of headers for the response
    protected $headers = array();
    //response code for the raw header
	public $code = 200;
    //HTTP response body
    public $content = '';
    //content-type mime
	public $type = 'text/html';
    //HTTP response codes
	private static $msgs = array(
           // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
	);

    /**
     * Response constructor.
     *
     * @param string $content HTTP response body
     * @param string $type Content-type mime, default 'text/html'
     * @param int $code Response code for the raw header, default 200
     */
	public function __construct($content = '', $type = 'text/html', $code = 200){
		$this->code = $code;
		$this->content = $content;
		$this->type = $type;
        //header('Content-Type:' . $this->content_type);
		$this->setHeader('Content-Type', $this->type);
	}

    /**
     * The method's maintain is sending a response.
     */
	public function send(){
		$this->sendHeaders();
		$this->sendBody();
	}

    /**
     * Sets header of response
     * добавляем в массив headers (элементы для заголовка) оин элемент
     * @param $name
     * @param $value
     */
	public function setHeader($name, $value){
		$this->headers[$name] = $value;
	}

    /**
     * Sends header of response
     * формируем заголовок ответа
     */
    public function sendHeaders(){
        // сообщение берём из массива $msgs
		header($_SERVER['SERVER_PROTOCOL'].' '.$this->code.' '.self::$msgs[$this->code]);
        // достаем из ранее подготовленного массива headers остальные элементы заголовка
		foreach($this->headers as $key => $value){
			header(sprintf("%s: %s", $key, $value));
		}
	}

    /**
     * Sends body of response
     */
	public function sendBody(){
		echo $this->content;
	}
}