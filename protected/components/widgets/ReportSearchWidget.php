<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportSearchWidget
 *
 * @author nguyenpt
 */
class ReportSearchWidget extends CWidget {

    public $dateFrom, $dateTo, $model;

    public function run() {
        $this->render('reportSearch/_form', array(
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ));
    }

}