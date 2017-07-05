<?php
	class FxAtolAdmin  {
		use baseModuleAdmin;
		public $module;

        public function config(){
            $regedit = regedit::getInstance();
            $params = Array(
                'enable' => Array(
                    'boolean:active' => ''
                ),
                'options' => Array(
                    'string:login' => '',
                    'string:password' => '',
                    'string:groupCode' => '',
                    'string:inn' => '',
                    'string:address' => '',
                )
            );

            $mode = (string) getRequest('param0');
            if($mode == "do") {
                $params = $this->expectParams($params);
                $regedit->setVar('//modules/fx_atol/enable', $params['enable']['boolean:active']);

                $regedit->setVar('//modules/fx_atol/login', $params['options']['string:login']);
                $regedit->setVar('//modules/fx_atol/password', $params['options']['string:password']);
                $regedit->setVar('//modules/fx_atol/groupCode', $params['options']['string:groupCode']);
                $regedit->setVar('//modules/fx_atol/inn', $params['options']['string:inn']);
                $regedit->setVar('//modules/fx_atol/address', $params['options']['string:address']);
                $this->chooseRedirect();
            }

            $params['enable']['boolean:active'] = $regedit->getVal('//modules/fx_atol/enable');

            $params['options']['string:login'] = $regedit->getVal('//modules/fx_atol/login');
            $params['options']['string:password'] = $regedit->getVal('//modules/fx_atol/password');
            $params['options']['string:groupCode'] = $regedit->getVal('//modules/fx_atol/groupCode');
            $params['options']['string:inn'] = $regedit->getVal('//modules/fx_atol/inn');
            $params['options']['string:address'] = $regedit->getVal('//modules/fx_atol/address');

            $this->setDataType('settings');
            $this->setActionType('modify');
            $data = $this->prepareData($params, 'settings');

            $this->setData($data);
            return $this->doData();
        }
	};
?>