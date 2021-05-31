<?php
namespace MElaraby\Emerald\Helpers;

use App\Packages\{Services\PushNotification\Google\Firebase};
use Illuminate\Support\Facades\Notification;

trait NotificationHelper
{
    /**
     * send push notification via google messaging cloud
     *
     * @param string|null $token
     * @param string $body
     * @param string|null $firebaseKey
     * @param string|null $title
     * @param string|null $image
     * @param null $data
     */
    private function sendMobileNotification(?string $token, string $body, string $firebaseKey = null, string $title = null, string $image = null, $data = null): void
    {
        (new Firebase())->sendNotification($token, !empty($title) ? $title : config('app.name') , $body, $image, $data);
    }

    /**
     * @param User $user
     * @param array $details
     * @param null $notificationClass
     * @param bool $work
     */
    private function storeNotification(User $user, array $details, $notificationClass = null, bool $work = false): void
    {
        if ($work) {
            Notification::send($user, new $notificationClass($details));
        }
    }
}
