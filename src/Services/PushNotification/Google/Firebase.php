<?php

namespace App\Packages\Services\PushNotification\Google;

class Firebase
{
    /**
     * @param $token
     * @param $title
     * @param $body
     * @param string|null $image
     */
    public function sendNotification($token, $title, $body, string $image = null, $data = []): void
    {
        $url = "https://fcm.googleapis.com/fcm/send";

        $json = json_encode(['to' => $token,
            'notification' => ['title' => $title,
                'text' => $body,
                'image' => $image,
                'sound' => 'default',
                'badge' => '1'
            ],
            'priority' => 'high']);

        $headers = [];

        $headers[] = 'Content-Type: application/json';

        $headers[] = 'Authorization: key=' . $this->getServerKey();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }

        curl_close($ch);
    }

    /**
     * @return mixed
     */
    public function getServerKey(): string
    {
        return 'AAAAlb8IWSY:APA91bGienDWpaj1p9_ET-vVtH2pVmUWRpHDhknes-ya7bDAyFyj9EFEahM86EUUgqReu4MCnpE0mKbSJbojU6lT9mEbjfEq479uEeMkXAuyAhNhdfa4vJzceC-8U7B3DQstm6sWtWiH';
    }
}
