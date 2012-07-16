<?php
class FormzGetListProcessor extends modObjectGetListProcessor {
    /* Class in model directory */
    public $classKey = 'fmzFormsData';

    /* Language package to load */
    public $languageTopics = array('formz:default');

    /* Field t sort by and direction */
    public $defaultSortField = 'senton';
    public $defaultSortDirection = 'ASC';

    /* Used to load the correct language error message */
    public $objectType = 'formz.form';

    /* Search database from backend module */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
    	$form = $this->getProperty('formId');
    	if (!empty($form)) {
    		$c->where(array(
	    		'form_id' => $form,
	    	));
    	}
    	return $c;
    }

    /**
     * Iterate through submitted forms and get related data
     * @param array $list
     * @return array
     */
    public function afterIteration($list) {
        $currentIndex = 0;
        $lists = array();
        foreach ($list as $item) {
            $formData = unserialize($item['data']);
            $fieldsData = $this->modx->getCollection('fmzFormsDataFields', array('data_id' => $item['id']));

            $lists[] = $item;
            $lists[$currentIndex]['ip_address'] = !empty($formData['ip_address']) ? $formData['ip_address'] : '';

            foreach ($fieldsData as $fd) {
                $lists[$currentIndex]['fields'][] = array(
                    'label' => $fd->label,
                    'value' => $fd->value,
                );
            }

            $currentIndex++;
        }
        return !empty($lists) ? $lists : $list;
    }
}

return 'FormzGetListProcessor';
