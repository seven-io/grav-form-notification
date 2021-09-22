<?php

namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Uri;

/**
 * Class Sms77FormNotificationPlugin
 * @package Grav\Plugin
 */
class Sms77FormNotificationPlugin extends Plugin {
    /**
     * @return array
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents() {
        return ['onPluginsInitialized' => ['onPluginsInitialized', 0]];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized() {
        if ($this->isAdmin() || !isset($_POST['__form-name__'])) return;

        $this->enable(['onFormProcessed' => ['onFormProcessed', 0]]);
    }

    public function onFormProcessed() {
        $formName = isset($_POST['__form-name__'])
            ? trim(strip_tags($_POST['__form-name__'])) : '';

        $replaceFormName = static function (string $message) use ($formName): string {
            return trim(str_replace('{{FORM_NAME}}', $formName, $message));
        };

        $msgParams = [];

        if ($this->getConfigValue('enable_notification')) {
            $to = $this->getConfigValue('to');

            if ($to) {
                $message = $this->getConfigValue('message');
                if ($message) $msgParams[] =
                    ['text' => $replaceFormName($message), 'to' => $to];
            }
        }

        if ($this->getConfigValue('enable_notification_user')) {
            $phoneField = $this->getConfigValue('phone_field');
            $phone = isset($_POST['data'][$phoneField])
            && '' !== $_POST['data'][$phoneField] ? $_POST['data'][$phoneField] : '';

            if ($phone) {
                $msg = $this->getConfigValue('user_response_msg');

                if ($msg) $msgParams[] =
                    ['text' => $replaceFormName($msg), 'to' => $phone];
            }
        }

        $data = ['error' => [], 'submitted' => count($msgParams) ? 'yes' : 'no'];
        foreach ($msgParams as $params) {
            $res = $this->post($this->getConfigValue('msg_type'), $params);
            $succeeded = in_array($res['success'], [100, 101]);

            if (!$succeeded) $data['error'][] = $res['success'];

            switch ($data['submitted']) {
                case 'no':
                    if ($succeeded) $data['submitted'] = 'partial';
                    break;
                case 'partial':
                    break;
                case 'yes':
                    if (!$succeeded) $data['submitted'] = 'partial';
                    break;
            }
        }

        $data['error'] = implode(',', $data['error']);

        $this->grav->redirect(
            $this->getReferrer($this->grav['uri']) . '?' . http_build_query($data));
    }

    private function getConfigValue($key) {
        return $this->config->get('plugins.sms77-form-notification.' . $key);
    }

    private function post(string $endpoint, array $body): array {
        $apiKey = $this->getConfigValue('api_key');
        $body['from'] = $this->getConfigValue('from');
        $body['json'] = 1;
        //$body['apiKey'] = $apiKey;
        //dd($body);
        $ch = curl_init('https://gateway.sms77.io/api/' . $endpoint);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            // 'Accept: application/json',
            'Content-type: application/json',
            'SentWith: Grav',
            'X-Api-Key: ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    /**
     * Get base referrer.
     * @param Uri $uri
     * @return string
     */
    private function getReferrer(Uri $uri): string {
        $referer = explode('?', $uri->referrer('/'));
        return isset($referer[0]) ? trim($referer[0]) : '/';
    }
}
