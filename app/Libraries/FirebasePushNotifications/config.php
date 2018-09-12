<?php
/**
 * Created by PhpStorm.
 * User: Hassan Saeed
 * Date: 2/22/2018
 * Time: 4:14 PM
 */

namespace App\Libraries\FirebasePushNotifications;


class config
{

    public $key;

    public function __construct($key)
    {

        $this->key = "AAAARjiKmhE:APA91bEK_5sSQjZe86ELZSlk3bFIqw7QzSf3nHYx-xT2wmSDbl9ojpwAajobZhbSLu_QG4DQT4Uh5cmC8H7YlScGqAo_BP4Bj78zYUb0IxJ4gy6s6Ojtyi3OpHmnMnZORx7m5TDi4ete";
    }

    public function getKey()
    {
        return $this->key;
    }

}