<?php
namespace App\CustomClass;

class LineNotify
{
    protected $token;

    public const URL = 'https://notify-api.line.me/api/notify';

    public function __construct()
    {
        $this->token = "cgkHv6CYJ3TZbwF5qydGozfKsR53FcNYbfCCwmVepo1"; //ใส่Token ที่copy เอาไว้
    }

    /**
     * @param string $message
     * @return array
     */
    protected function params(string $message): array
    {
        return [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                . "Authorization: Bearer " . $this->token . "\r\n"
                . "Content-Length: " . strlen($message) . "\r\n",
                'content' => $message
            ]
        ];
    }

    public function notifyMessage($message)
    {
        $message = [
            'message' => $message
        ];
        $message = http_build_query($message, '', '&');
        $context = stream_context_create($this->params($message));
        $result = file_get_contents(static::URL, false, $context);
        $response = json_decode($result);

        return $response;
    }
}
