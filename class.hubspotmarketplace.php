<?php

class HubSpotMarketplace{  

    protected $appSecret;
    protected $marketplaceSignature;
    protected $marketplaceCaller;
    protected $marketplaceUserId;
    protected $marketplacePortalId;
    protected $marketplaceAppName;
    protected $marketplaceAppCallbackURL;
    protected $marketplaceAppPageURL;
    protected $marketplaceAppCanvasURL;

    /**
    * Constructor.
    *
    * @param $request: The $_REQUEST array from the HTTP request
    * @param $appSecret: Your app secret key
    **/
    function __construct($request, $appSecret) {
        $this->appSecret = $appSecret;
        $this->marketplaceSignature = $request['hubspot_marketplace_signature'];
        $this->marketplaceCaller = $request['hubspot_marketplace_caller'];
        $this->marketplaceUserId = $request['hubspot_marketplace_user_id'];
        $this->marketplacePortalId = $request['hubspot_marketplace_portal_id'];
        $this->marketplaceAppName = $request['hubspot_marketplace_app_name'];
        $this->marketplaceAppCallbackURL = $request['hubspot_marketplace_app_callbackUrl'];
        $this->marketplaceAppPageURL = $request['hubspot_marketplace_app_pageUrl'];
        $this->marketplaceAppCanvasURL = $request['hubspot_marketplace_canvasUrl'];
    }

    /**
    * Verifies that request is from HubSpot Marketplace
    *
    * @returns boolean true if request is verfied, false if not verified
    **/
    public function verifyRequest() {
        return $this->parseSignedRequest($this->marketplaceSignature);
    }

    /**
    * @returns String value of hubspot_marketplace_caller
    **/
    public function getCaller() {
        return $this->marketplaceCaller;
    }

    /**
    * @returns String value of hubspot_marketplace_user_id
    **/
    public function getUserId() {
        return $this->marketplaceUserId;
    }

    /**
    * @returns String value of hubspot_marketplace_portal_id
    **/
    public function getPortalId() {
        return $this->marketplacePortalId;
    }

    /**
    * @returns String value of hubspot_marketplace_app_name
    **/
    public function getAppName() {
        return $this->marketplaceAppName;
    }

    /**
    * @returns String value of hubspot_marketplace_app_callbackUrl
    **/
    public function getAppCallbackURL() {
        return $this->marketplaceAppCallbackURL;
    }

    /**
    * @returns String value of hubspot_marketplace_app_pageUrl
    **/
    public function getAppPageURL() {
        return $this->marketplaceAppPageURL;
    }

    /**
    * @returns String value of hubspot_marketplace_app_canvasUrl
    **/
    public function getAppCanvasURL() {
        return $this->marketplaceAppCanvasURL;
    }

    /**
    * Parses and decodes hubspot_marketplace_signature to verify
    * that request is from HubSpot
    *
    * @param $marketplaceSignature: The encoded signature from $_REQUEST['hubspot_marketplace_signature']
    *
    * @returns boolean true if request is verified, false if not verified
    **/
    protected function parseSignedRequest($marketplaceSignature) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64UrlDecode($encoded_sig);
        $data = $this->base64UrlDecode($payload);

        // check sig
        $expected_sig = hash_hmac('sha1', $data,
                                  $this->appSecret, $raw = true);

        if ($sig != $expected_sig) {
            return false;
        }

        return true;
    }

    /**
    * Decodes base64 encoded data
    *
    * @param $input: base 64 encoded string
    *
    * @returns decoded string
    **/
    protected function base64UrlDecode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
?>