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
        }
        return SiteConfig::current_site_config();
    }

	public function updateSettingsFields(&$fields) {
		$fields->addFieldToTab(
			"Root.Settings",
			$contentfooter = new FieldGroup(
				new CheckboxField('ShowSharingLinks', "")
			),
			'CanViewType'
		);
		$contentfooter->setTitle(_t("Page.ShowSharingLinks", 'Show Sharing Links/Buttons on this page'));
	}

    public function FacebookShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'http://facebook.com/sharer/sharer.php?u=' . Director::absoluteURL($url);
    }

    public function TwitterShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://twitter.com/share?url=' . Director::absoluteURL($url);
    }

    public function GoogleShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://plus.google.com/share?url=' . Director::absoluteURL($url);
    }

    public function LinkedinShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return 'https://www.linkedin.com/shareArticle?mini=true&amp;url=' . Director::absoluteURL($url) . '&amp;title=' . urlencode(($this->owner->hasMethod('getSocialMetaPageTitle')) ? $this->owner->getSocialMetaPageTitle() : $this->owner->getTitle());
    }

    public function PinterestShareLink($base = false){
    	$url = $base ? Director::absoluteBaseURL() : $this->owner->Link();
        return new ArrayData(array(
        	'Link' => 'http://pinterest.com/pin/create/button/?url=' . Director::absoluteURL($url),
        	'Image' => ($this->owner->hasMethod('getSocialMetaImage') && $this->owner->getSocialMetaImage() ? $this->owner->getSocialMetaImage()->getAbsoluteURL() : null),
        	'Description' => urlencode(($this->owner->hasMethod('getSocialMetaPageTitle')) ? $this->owner->getSocialMetaPageTitle() : $this->owner->getTitle()),
        ));
    }

}

