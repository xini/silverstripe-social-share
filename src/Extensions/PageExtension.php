<?php

namespace Innoweb\SocialShare\Extensions;

use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;

class PageExtension extends \SilverStripe\CMS\Model\SiteTreeExtension {

	private static $db = array(
		"ShowSharingLinks" => "Boolean",
	);

	public function getSocialShareConfig() {
        if (class_exists('Symbiote\Multisites\Multisites') && !Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings')) {
            return \Symbiote\Multisites\Multisites::inst()->getCurrentSite();
        } elseif (class_exists('Fromholdio\ConfiguredMultisites\Multisites') && !Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings')) {
            return \Fromholdio\ConfiguredMultisites\Multisites::inst()->getCurrentSite();
        } else {
            return SiteConfig::current_site_config();
        }
    }

	public function updateSettingsFields(&$fields) {
		$fields->addFieldToTab(
			"Root.Settings",
			$contentfooter = new FieldGroup(
				new CheckboxField('ShowSharingLinks', _t("Page.ShowSharingLinks", 'Show Sharing Links/Buttons on this page'))
			),
			'CanViewType'
		);
		$contentfooter->setTitle(_t("Page.SocialSharing", 'Social Sharing'));
	}

    public function FacebookShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://facebook.com/sharer/sharer.php?u=' . Director::absoluteURL($url);
    }

    public function TwitterShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://twitter.com/share?url=' . Director::absoluteURL($url);
    }

    public function BlueskyShareLink($base = false){
        $url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://bsky.app/intent/compose?text=' . urlencode(Director::absoluteURL($url));
    }

    public function ThreadsShareLink($base = false){
        $url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://threads.net/intent/post?text=' . urlencode(Director::absoluteURL($url));
    }

    public function RedditShareLink($base = false){
        $url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://reddit.com/submit?url=' . urlencode(Director::absoluteURL($url));
    }

    public function LinkedinShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://www.linkedin.com/sharing/share-offsite/?url=' . Director::absoluteURL($url);
    }

    public function PinterestShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return new ArrayData(array(
        	'Link' => 'https://pinterest.com/pin/create/button/?url=' . Director::absoluteURL($url),
        	'Image' => ($this->owner->hasMethod('getSocialMetaImage') && $this->owner->getSocialMetaImage() ? $this->owner->getSocialMetaImage()->getAbsoluteURL() : null),
        	'Description' => urlencode(($this->owner->hasMethod('getSocialMetaPageTitle')) ? $this->owner->getSocialMetaPageTitle() : $this->owner->getTitle()),
        ));
    }

}

