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

class FacebookEvents
{
    protected static $access_token = "";
    protected static $pixel_id = '';

    function sendContentView($post)
    {
        global $wp;
        $api = Api::init(null, null, self::$access_token);
        $api->setLogger(new CurlLogger());

        $user_data = (new UserData())
            // ->setEmails(array($customer->user_email))
            // ->setPhones(array('5550724875'))
            // It is recommended to send Client IP and User Agent for Conversions API Events.
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            // ->setClientIpAddress("189.217.18.121")
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            // ->setClientUserAgent("Mozilla/5.0 (Windows NT 6.1; rv:16.0) Gecko/20100101 Firefox/16.0")
            ->setFbc($_COOKIE["_fbc"])
            ->setFbp($_COOKIE["_fbp"]);

        $event = (new Event())
            ->setEventName('ViewContent')
            ->setEventTime(time())
            ->setEventSourceUrl(home_url(add_query_arg(array($_GET), $wp->request)))
            ->setUserData($user_data)
            ->setActionSource(ActionSource::WEBSITE);

        $events = array();
        array_push($events, $event);

        $request = (new EventRequest(self::$pixel_id))
            ->setEvents($events);
        $response = $request->execute();
        $response->getFbTraceId();
    }

    function sendPageView($post)
    {
        global $wp;
        $api = Api::init(null, null, self::$access_token);
        $api->setLogger(new CurlLogger());

        $user_data = (new UserData())
            // ->setEmails(array($customer->user_email))
            // ->setPhones(array('5550724875'))
            // It is recommended to send Client IP and User Agent for Conversions API Events.
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            // ->setClientIpAddress("189.217.18.121")
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            // ->setClientUserAgent("Mozilla/5.0 (Windows NT 6.1; rv:16.0) Gecko/20100101 Firefox/16.0")
            ->setFbc($_COOKIE["_fbc"])
            ->setFbp($_COOKIE["_fbp"]);

        $event = (new Event())
            ->setEventName('PageView')
            ->setEventTime(time())
            ->setEventSourceUrl(home_url(add_query_arg(array($_GET), $wp->request)))
            ->setUserData($user_data)
            ->setActionSource(ActionSource::WEBSITE);

        $events = array();
        array_push($events, $event);

        $request = (new EventRequest(self::$pixel_id))
            ->setEvents($events);
        $response = $request->execute();
        $response->getFbTraceId();
    }

    /**
     * sendPurchase
     * @version 0.1
     * Envio de evento Purchase a Facebook
     */
    function sendPurchase($order_id)
    {
        global $wp;
        $order = wc_get_order($order_id);
        $customer = $order->get_user();


        $api = Api::init(null, null, self::$access_token);
        $api->setLogger(new CurlLogger());

        $user_data = (new UserData())
            // ->setEmails(array($customer->user_email))
            // ->setPhones(array('5550724875'))
            // It is recommended to send Client IP and User Agent for Conversions API Events.
            ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
            // ->setClientIpAddress("189.217.18.121")
            ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
            // ->setClientUserAgent("Mozilla/5.0 (Windows NT 6.1; rv:16.0) Gecko/20100101 Firefox/16.0")
            ->setFbc($_COOKIE["_fbc"])
            ->setFbp($_COOKIE["_fbp"]);

        $content = array();
        $items = $order->get_items();
        foreach ($items as $item) {
            // $product_name = $item->get_name();
            // $product_id = $item->get_product_id();
            // $product_variation_id = $item->get_variation_id();
            array_push($content, (new Content())
                ->setProductId($item->get_product_id())
                ->setQuantity($item['qty'])
                ->setDeliveryCategory(DeliveryCategory::HOME_DELIVERY));
        }


        $custom_data = (new CustomData())
            ->setContents($content)
            ->setCurrency($order->get_currency())
            ->setValue($order->get_total());

        $event = (new Event())
            ->setEventName('Purchase')
            ->setEventTime(time())
            ->setEventSourceUrl(home_url(add_query_arg(array($_GET), $wp->request)))
            ->setUserData($user_data)
            ->setCustomData($custom_data)
            ->setActionSource(ActionSource::WEBSITE);

        $events = array();
        array_push($events, $event);

        $request = (new EventRequest(self::$pixel_id))
            ->setEvents($events);
        $response = $request->execute();
        //print_r($response);
    }
}
