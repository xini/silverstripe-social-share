<?php

namespace Innoweb\SocialShare\Extensions;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;
use UncleCheese\DisplayLogic\Forms\Wrapper;

class ConfigExtension extends DataExtension {

	private static $db = array(
		'ShareOnFacebook' => 'Boolean',
		'ShareOnTwitter' => 'Boolean',
		'ShareOnLinkedin' => 'Boolean',
		'ShareOnPinterest' => 'Boolean',
        'ShareOnBluesky' => 'Boolean',
        'ShareOnThreads' => 'Boolean',
        'ShareOnReddit' => 'Boolean',

		'SharingType' => "Enum('Links,Buttons,AddThis','Links')",
	    'ShareAddThisCode' => 'Text',

		'DefaultSharingTitle' => 'Varchar(255)',
		'DefaultSharingDescription' => 'Text',
	);

	private static $has_one = array(
	    'DefaultSharingImage' => Image::class
	);

	private static $owns = [
	    'DefaultSharingImage'
	];

	public function updateCMSFields(FieldList $fields) {

		if (
            (!class_exists(\Symbiote\Multisites\Multisites::class) && !class_exists(\Fromholdio\ConfiguredMultisites\Multisites::class))
			|| (Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings') && $this->owner instanceof SiteConfig)
			|| (!Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings') && $this->owner instanceof \Symbiote\Multisites\Model\Site)
            || (!Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings') && $this->owner instanceof \Fromholdio\ConfiguredMultisites\Model\Site)
		) {

            // sharing
            $restrictedType = false;
            if (($type = $this->getOwner()->config()->get('restrict_sharing_type'))
                && in_array($type, ['Links', 'Buttons', 'AddThis'])
            ) {
                $restrictedType = $type;
            }

            if (!$restrictedType) {
                $fields->addFieldToTab(
                    "Root.SocialSharing",
                    DropdownField::create(
                        'SharingType',
                        _t("SocialShareConfigExtension.SharingType", 'Type of sharing links'),
                        array(
                            'Links' => 'Normal links (no Javascript needed)',
                            'Buttons' => 'Native sharing buttons (uses Javascript, not all platforms supported)',
                            'AddThis' => 'AddThis (uses Javascript)'
                        )
                    )
                );
            }
            if (!$restrictedType || $restrictedType == 'Links' || $restrictedType == 'Buttons') {
                $fields->addFieldToTab(
                    "Root.SocialSharing",
                    $manualFields = Wrapper::create(
                        FieldGroup::create(
                            CheckboxField::create('ShareOnFacebook', _t("SocialShareConfigExtension.SHAREONFACEBOOK", 'Share on Facebook')),
                            CheckboxField::create('ShareOnBluesky', _t("SocialShareConfigExtension.SHAREONBLUESKY", 'Share on Bluesky')),
                            CheckboxField::create('ShareOnThreads', _t("SocialShareConfigExtension.SHAREONTHREADS", 'Share on Threads')),
                            CheckboxField::create('ShareOnTwitter', _t("SocialShareConfigExtension.SHAREONTWITTER", 'Share on X (Twitter)')),
                            CheckboxField::create('ShareOnLinkedin', _t("SocialShareConfigExtension.SHAREONLINKEDIN", 'Share on LinkedIn')),
                            CheckboxField::create('ShareOnPinterest', _t("SocialShareConfigExtension.SHAREONPINTEREST", 'Share on Pinterest')),
                            CheckboxField::create('ShareOnReddit', _t("SocialShareConfigExtension.SHAREONREDDIT", 'Share on Reddit'))
                        )
                            ->addExtraClass('social-sharing-networks')
                            ->setTitle(_t("SocialShareConfigExtension.SocialNetworks", 'Social Networks'))
                    )
                );
            }
            if (!$restrictedType) {
                $manualFields->displayIf('SharingType')->isEqualTo('Links')->orIf('SharingType')->isEqualTo('Buttons');
            }

            if (!$restrictedType || $restrictedType == 'AddThis') {
                $fields->addFieldToTab(
                    "Root.SocialSharing",
                    $addThisField = TextareaField::create("ShareAddThisCode", _t('SocialShareConfigExtension.AddThisCode', 'AddThis Code'))
                        ->setRightTitle('Go to www.addthis.com/dashboard to customize your tools')
                );
            }
            if (!$restrictedType) {
                $addThisField->displayIf('SharingType')->isEqualTo('AddThis');
            }

			// sharing data
			$fields->addFieldsToTab(
				"Root.SocialSharing",
				array(
				    HeaderField::create("sharingdataheader", _t("SocialShareConfigExtension.DefaultSharingData", 'Default Sharing Data'), 2),
					TextField::create("DefaultSharingTitle", _t("SocialShareConfigExtension.DEFAULTSHARINGTITLE", 'Default Site Name')),
					TextareaField::create("DefaultSharingDescription", _t("SocialShareConfigExtension.DEFAULTSHARINGDESCRIPTION", 'Default Description'))
						->setRows(5),
				    UploadField::create("DefaultSharingImage", _t("SocialShareConfigExtension.DefaultSharingImage", 'Default Image (1200x630px)'))
    				    ->setFolderName('social')
    				    ->setAllowedExtensions(array('jpg', 'gif', 'png')),
				)
			);

			// set tab titles
			$fields->fieldByName("Root.SocialSharing")->setTitle(_t('SocialShareConfigExtension.SocialSharingTab', 'Social Sharing'));

		}
	}

	public function updateSiteCMSFields(FieldList $fields) {
		$this->updateCMSFields($fields);
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();

        if (($type = $this->getOwner()->config()->get('restrict_sharing_type'))
            && in_array($type, ['Links', 'Buttons', 'AddThis'])
        ) {
            $this->getOwner()->SharingType = $type;
        }

		// clean up data
		if ($this->owner->SharingType == "Links" || $this->owner->SharingType == "Buttons") {
		    $this->owner->ShareAddThisCode = "";
		} else if ($this->owner->MicroDataType == "AddThis") {
		    $this->owner->ShareOnFacebook = false;
		    $this->owner->ShareOnBluesky = false;
            $this->owner->ShareOnTwitter = false;
		    $this->owner->ShareOnLinkedin = false;
		    $this->owner->ShareOnPinterest = false;
            $this->owner->ShareOnThreads = false;
            $this->owner->ShareOnReddit = false;
		}

	}

}
