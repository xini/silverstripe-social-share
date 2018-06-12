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
		'ShareOnGoogle' => 'Boolean',
		'ShareOnLinkedin' => 'Boolean',
		'ShareOnPinterest' => 'Boolean',

		'SharingType' => "Enum('Links,Buttons,AddThis','Links')",
	    'ShareAddThisCode' => 'Text',

		'DefaultSharingTitle' => 'Varchar(255)',
		'DefaultSharingDescription' => 'Text',
	);

	private static $has_one = array(
	    'DefaultSharingImage' => Image::class
	);

	public function updateCMSFields(FieldList $fields) {

		if (
			!class_exists('Symbiote\Multisites\Multisites')
			|| (Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings') && $this->owner instanceof SiteConfig)
			|| (!Config::inst()->get(ConfigExtension::class, 'multisites_enable_global_settings') && $this->owner instanceof \Symbiote\Multisites\Model\Site)
		) {

			// sharing
			$fields->addFieldToTab(
				"Root.SocialSharing",
			    DropdownField::create(
			        'SharingType',
			        _t("SocialShareConfigExtension.SharingType", 'Type of sharing links'),
			        array(
			            'Links' => 'Normal links (no Javascript needed)',
			            'Buttons' => 'Native sharing buttons (uses Javascript)',
			            'AddThis'=> 'AddThis (uses Javascript)'
			        )
			    )
			);
			$fields->addFieldToTab(
				"Root.SocialSharing",
				$manualFields = Wrapper::create(
                    FieldGroup::create(
                        CheckboxField::create('ShareOnFacebook', _t("SocialShareConfigExtension.SHAREONFACEBOOK", 'Share on Facebook')),
                        CheckboxField::create('ShareOnTwitter', _t("SocialShareConfigExtension.SHAREONTWITTER", 'Share on Twitter')),
                        CheckboxField::create('ShareOnGoogle', _t("SocialShareConfigExtension.SHAREONGOOGLE", 'Share on Google+')),
                        CheckboxField::create('ShareOnLinkedin', _t("SocialShareConfigExtension.SHAREONLINKEDIN", 'Share on LinkedIn')),
                        CheckboxField::create('ShareOnPinterest', _t("SocialShareConfigExtension.SHAREONPINTEREST", 'Share on Pinterest'))
                    )
                        ->addExtraClass('social-sharing-networks')
                        ->setTitle(_t("SocialShareConfigExtension.SocialNetworks", 'Social Networks'))
                )
			);
			$fields->addFieldToTab(
			    "Root.SocialSharing",
			    $addThisField = TextareaField::create("ShareAddThisCode", _t('SocialShareConfigExtension.AddThisCode', 'AddThis Code'))
			        ->setRightTitle('Go to www.addthis.com/dashboard to customize your tools')
			);

			$manualFields->displayIf('SharingType')->isEqualTo('Links')->orIf('SharingType')->isEqualTo('Buttons');
            $addThisField->displayIf('SharingType')->isEqualTo('AddThis');

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

		// clean up data
		if ($this->owner->SharingType == "Links" || $this->owner->SharingType == "Buttons") {
		    $this->owner->ShareAddThisCode = "";
		} else if ($this->owner->MicroDataType == "AddThis") {
		    $this->owner->ShareOnFacebook = false;
		    $this->owner->ShareOnTwitter = false;
		    $this->owner->ShareOnGoogle = false;
		    $this->owner->ShareOnLinkedin = false;
		    $this->owner->ShareOnPinterest = false;
		}

	}

}
