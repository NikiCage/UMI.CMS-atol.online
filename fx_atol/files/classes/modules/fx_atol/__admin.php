<?php
	abstract class __fx_atol extends baseModuleAdmin {

        public function config(){
            $regedit = regedit::getInstance();
            $objectTypesColl = umiObjectTypesCollection::getInstance();
            $params = Array(
                'enable' => Array(
                    'boolean:active' => ''
                ),
                'options' => Array(
                    'string:login' => '',
                    'string:password' => '',
                    'string:groupCode' => '',
                    'guide:def_tax' => array('type-id' => $objectTypesColl->getTypeIdByGUID('emarket-tax'), 'value' => null),
                    'select:sno' => NULL,
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
                $regedit->setVar('//modules/fx_atol/def_tax', $params['options']['guide:def_tax']);
                $regedit->setVar('//modules/fx_atol/sno', $params['options']['select:sno']);
                $regedit->setVar('//modules/fx_atol/inn', $params['options']['string:inn']);
                $regedit->setVar('//modules/fx_atol/address', $params['options']['string:address']);
                $this->chooseRedirect();
            }

            $sno = Array(
                'osn' => getLabel('atol-sno-osn'), // общая СН
                'usn_income' => getLabel('atol-sno-usn_income'), // упрощенная СН (доходы)
                'usn_income_outcome' => getLabel('atol-sno-usn_income_outcome'), // упрощенная СН (доходы минус расходы)
                'envd' => getLabel('atol-sno-envd'), // единый налог на вмененный доход
                'esn' => getLabel('atol-sno-esn'), // единый сельскохозяйственный налог
                'patent' => getLabel('atol-sno-patent') // патентная СН
            );

            if ($regedit->getVal('//modules/fx_atol/sno')) {
                $sno['value'] = $regedit->getVal('//modules/fx_atol/sno');
            } else {
                $sno['value'] = 'osn';
            }

            $params['enable']['boolean:active'] = $regedit->getVal('//modules/fx_atol/enable');

            $params['options']['string:login'] = $regedit->getVal('//modules/fx_atol/login');
            $params['options']['string:password'] = $regedit->getVal('//modules/fx_atol/password');
            $params['options']['string:groupCode'] = $regedit->getVal('//modules/fx_atol/groupCode');
            $params['options']['guide:def_tax']['value'] = $regedit->getVal('//modules/fx_atol/def_tax');
            $params['options']['select:sno'] = $sno;
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