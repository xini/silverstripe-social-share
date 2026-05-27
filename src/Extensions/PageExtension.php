<?php

namespace Innoweb\SocialShare\Extensions;

use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Model\ArrayData;
use SilverStripe\SiteConfig\SiteConfig;
use Symbiote\Multisites\Multisites;

class PageExtension extends Extension
{
    private static $db = [
        "ShowSharingLinks" => "Boolean",
    ];

    public function getSocialShareConfig()
    {
        if (class_exists('Symbiote\Multisites\Multisites') && !Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings')) {
            return Multisites::inst()->getCurrentSite();
        } elseif (class_exists('Fromholdio\ConfiguredMultisites\Multisites') && !Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings')) {
            return \Fromholdio\ConfiguredMultisites\Multisites::inst()->getCurrentSite();
        } else {
            return SiteConfig::current_site_config();
        }
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName('ShowSharingLinks');
    }

    public function updateSettingsFields(&$fields)
    {
        $fields->addFieldToTab(
            "Root.Settings",
            $contentfooter = FieldGroup::create(CheckboxField::create('ShowSharingLinks', _t("Page.ShowSharingLinks", 'Show Sharing Links/Buttons on this page'))),
            'CanViewType'
        );
        $contentfooter->setTitle(_t("Page.SocialSharing", 'Social Sharing'));
    }

    public function FacebookShareLink($base = false)
    {
        $url = $base ? Director::absoluteBaseURL() : $this->getOwner()->Link();
        return 'https://facebook.com/sharer/sharer.php?u=' . Director::absoluteURL($url);
    }

    public function TwitterShareLink($base = false)
    {
        $url = $base ? Director::absoluteBaseURL() : $this->getOwner()->Link();
        return 'https://twitter.com/share?url=' . Director::absoluteURL($url);
    }

    public function BlueskyShareLink($base = false)
    {
        $url = $base ? Director::absoluteBaseURL() : $this->getOwner()->Link();
        return 'https://bsky.app/intent/compose?text=' . urlencode(Director::absoluteURL($url));
    }

    public function ThreadsShareLink($base = false)
    {
        $url = $base ? Director::absoluteBaseURL() : $this->getOwner()->Link();
        return 'https://threads.net/intent/post?text=' . urlencode(Director::absoluteURL($url));
    }

    public function RedditShareLink($base = false)
    {
        $url = $base ? Director::absoluteBaseURL() : $this->getOwner()->Link();
        return 'https://reddit.com/submit?url=' . urlencode(Director::absoluteURL($url));
    }

    public function LinkedinShareLink($base = false)
    {
        $url = $base ? Director::absoluteBaseURL() : $this->getOwner()->Link();
        return 'https://www.linkedin.com/sharing/share-offsite/?url=' . Director::absoluteURL($url);
    }

    public function PinterestShareLink($base = false)
    {
        $url = $base ? Director::absoluteBaseURL() : $this->getOwner()->Link();
        return ArrayData::create([
            'Link' => 'https://pinterest.com/pin/create/button/?url=' . Director::absoluteURL($url),
            'Image' => ($this->getOwner()->hasMethod('getSocialMetaImage') && $this->getOwner()->getSocialMetaImage() ? $this->getOwner()->getSocialMetaImage()->getAbsoluteURL() : null),
            'Description' => urlencode(($this->getOwner()->hasMethod('getSocialMetaPageTitle')) ? $this->getOwner()->getSocialMetaPageTitle() : $this->getOwner()->getTitle()),
        ]);
    }
}
