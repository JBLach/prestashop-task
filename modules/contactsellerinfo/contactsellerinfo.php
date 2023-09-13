<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class ContactSellerInfo extends Module
{
    public function __construct()
    {
        $this->name = 'contactsellerinfo';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Jakub Łach';
        $this->need_instance = 0;

        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => '8.99.99',
        ];

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Contact Seller Info');
        $this->description = $this->l('Displays seller info on the contact page.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }
    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('displayContactSellerInfo') ||
            !Configuration::updateValue('SELLER_NIP', '') ||
            !Configuration::updateValue('SELLER_REGON', '') ||
            !Configuration::updateValue('SELLER_KRS', '')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('SELLER_NIP') ||
            !Configuration::deleteByName('SELLER_REGON') ||
            !Configuration::deleteByName('SELLER_KRS')
        ) {
            return false;
        }
        return true;
    }
}