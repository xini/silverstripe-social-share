<?php

namespace Innoweb\SocialShare\Extensions;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

class PageControllerExtension extends Extension {

	public function onAfterInit() {

	    Requirements::css('innoweb/silverstripe-social-share:client/css/social-share.css');

	    $config = ($this->getOwner()->data() && $this->getOwner()->data()->hasMethod('getSocialShareConfig')) ? $this->getOwner()->data()->getSocialShareConfig() : null;

	    if ($config) {

    		$enableBaseShare = Config::inst()->get(ConfigExtension::class, 'enable_base_share');

    		// add javascript for sharing buttons
    		if (($this->owner->ShowSharingLinks || $enableBaseShare) && $config->SharingType == "Buttons") {

    			// twitter
    			if ($config->ShareOnTwitter) {
    				$js = <<<JS
    					!function(d,s,id){
    						var js,fjs=d.getElementsByTagName(s)[0];
    						if(!d.getElementById(id)){
    							js=d.createElement(s);
    							js.id=id;js.src="//platform.twitter.com/widgets.js";
    							fjs.parentNode.insertBefore(js,fjs);
    						}
    					}(document,"script","twitter-wjs");
JS;
    				Requirements::customScript($js, 'twitter-js');
    			}

    			// google plus
    			if ($config->ShareOnGoogle) {
    		   		$js = <<<JS
    					(function() {
    						var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    						po.src = 'https://apis.google.com/js/plusone.js';
    						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    					})();
JS;
    				Requirements::customScript($js, 'google-plus-js');
    			}

    			// facebook
    			if ($config->ShareOnFacebook) {
    		   		$js = <<<JS
    					(function(d, s, id) {
    						var js, fjs = d.getElementsByTagName(s)[0];
    						if (d.getElementById(id)) return;
    						js = d.createElement(s); js.id = id;
    						js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    						fjs.parentNode.insertBefore(js, fjs);
    					}(document, 'script', 'facebook-jssdk'));
JS;
    				Requirements::customScript($js, 'facebook-js');
    			}

    			// pinterest
    			if ($config->ShareOnPinterest) {
    		   		$js = <<<JS
    					(function(d){
    					    var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
    					    p.type = 'text/javascript';
    					    p.async = true;
    					    p.src = '//assets.pinterest.com/js/pinit.js';
    					    f.parentNode.insertBefore(p, f);
    					}(document));
JS;
    				Requirements::customScript($js, 'pinterest-js');
    			}

    			// linkedin
    			if ($config->ShareOnLinkedin) {
    		   		$js = <<<JS
    					(function() {
    						var e = document.createElement('script');
    						e.type = 'text/javascript';
    						e.async = true;
    						e.src = '//platform.linkedin.com/in.js?async=true';
    						e.onload = function(){IN.init() };
    						var s = document.getElementsByTagName('script')[0];
    						s.parentNode.insertBefore(e, s);
    					})();
JS;
    				Requirements::customScript($js, 'linkedin-js');
    			}

    		} else if (($this->owner->ShowSharingLinks || $enableBaseShare) && $config->SharingType == "AddThis" && $config->ShareAddThisCode) {

    		    Requirements::customScript($config->obj('ShareAddThisCode')->RAW(), 'addthis-js');

    		}
	    }
	}
}
