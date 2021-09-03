<?php
require __DIR__ . '/vendor/autoload.php';

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;

$access_token = "EAAVDn0jkmPsBAJCJONIYoMYC5B9btPl5ZAkES2NwhEYv73l8B6wtFuJC4ugLfwxbhcu3JEFiuefIt8y6aSkzTiatJBTX88Rc8IjJZBiGjBC8lZBwV5K4c9DmA7yYwL1lUAJbVQv7OoGWCZCvMibrHtvT0p2UYxJloD8lluJwi8sNVTt0lNA5cmsOuDMkhDcZD";
$pixel_id = '828657444355573';

$api = Api::init(null, null, $access_token);
$api->setLogger(new CurlLogger());

$user_data = (new UserData())
    ->setEmails(array('daniel@rebootproject.mx'))
    ->setPhones(array('5550724875'))
    // It is recommended to send Client IP and User Agent for Conversions API Events.
    //->setClientIpAddress($_SERVER['REMOTE_ADDR'])
    ->setClientIpAddress("189.217.18.121")
    //->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
    ->setClientUserAgent("Mozilla/5.0 (Windows NT 6.1; rv:16.0) Gecko/20100101 Firefox/16.0")
    ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
    ->setFbp('fb.1.1558571054389.1098115397');

$content = (new Content())
    ->setProductId('product123')
    ->setQuantity(1)
    ->setDeliveryCategory(DeliveryCategory::HOME_DELIVERY);

$custom_data = (new CustomData())
    ->setContents(array($content))
    ->setCurrency('usd')
    ->setValue(123.45);

$event = (new Event())
    ->setEventName('Purchase')
    ->setEventTime(time())
    ->setEventSourceUrl('http://jaspers-market.com/product/123')
    ->setUserData($user_data)
    ->setCustomData($custom_data)
    ->setActionSource(ActionSource::WEBSITE);

$events = array();
array_push($events, $event);

$request = (new EventRequest($pixel_id))
    ->setEvents($events);
$response = $request->execute();
print_r($response);
