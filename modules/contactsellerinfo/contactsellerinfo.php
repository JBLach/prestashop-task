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

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $nip = strval(Tools::getValue('SELLER_NIP'));
            $regon = strval(Tools::getValue('SELLER_REGON'));
            $krs = strval(Tools::getValue('SELLER_KRS'));

            Configuration::updateValue('SELLER_NIP', $nip);
            Configuration::updateValue('SELLER_REGON', $regon);
            Configuration::updateValue('SELLER_KRS', $krs);

            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('NIP'),
                    'name' => 'SELLER_NIP',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('REGON'),
                    'name' => 'SELLER_REGON',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('KRS'),
                    'name' => 'SELLER_KRS',
                    'size' => 20,
                    'required' => true
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;

        $helper->fields_value['SELLER_NIP'] = Configuration::get('SELLER_NIP');
        $helper->fields_value['SELLER_REGON'] = Configuration::get('SELLER_REGON');
        $helper->fields_value['SELLER_KRS'] = Configuration::get('SELLER_KRS');

        return $helper->generateForm($fieldsForm);
    }
}