<?php
/* @var $this ReceptionistController */

?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'medical-records-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="maincontent clearfix">
    <?php
     for ($index = 0; $index < 30 ; $index++) {
         CommonProcess::echoTest('Unique id: ', CommonProcess::generateUniqId());
     }
//        CommonProcess::echoTest('Unique id: ', CommonProcess::generateUniqId());
//    $listUser = Users::getListUserEmail();
//    $listUser = ScheduleEmail::handleBuildEmailResetPass();
//    CommonProcess::echoTest("Test list user's emails: ", count($listUser));
//    foreach ($listUser as $user) {
//        CommonProcess::echoTest("&nbsp;&nbsp;&nbsp;&nbsp;User: ", "($user->first_name) - ($user->email)");
//    }
//    CommonProcess::echoTest("Test email reset pass: ", ScheduleEmail::handleRunEmailResetPass());
    
    $from = time();
    // Test email content
    $user = Users::model()->findByAttributes(array(
        'username' => 'nguyenpt',
    ));
    if ($user) {
        CommonProcess::echoTest("Test email: ", $user->first_name);
        $date = date('d-m-Y');
        CommonProcess::echoTest("&nbsp;&nbsp;&nbsp;&nbsp;Current date: ", $date);
        $data = array($date, $user->first_name, $user->temp_password, 'nkvietmy.com');
        CommonProcess::echoTest("&nbsp;&nbsp;&nbsp;&nbsp;Data array: ", $data);
        $content = EmailTemplates::createEmailContent($data);
        CommonProcess::echoArrayKeyValue("&nbsp;&nbsp;&nbsp;&nbsp;Content array: ", $content);
        CommonProcess::echoTest("&nbsp;&nbsp;&nbsp;&nbsp;Email: ", $user->email);
//        $emailData = EmailHandler::sendTemplateMail(EmailTemplates::TEMPLATE_ID_RESET_PASSWORD, $content, $content, $user->email);
//        CommonProcess::echoArrayKeyValue("&nbsp;&nbsp;&nbsp;&nbsp;Email data: ", $emailData);
//        CommonProcess::echoTest("&nbsp;&nbsp;&nbsp;&nbsp;Body: ", $emailData);
    } else {
        CommonProcess::echoTest("Can not find user", '');
    }
    
//     SMSHandler::sendSMS('smsbrand_gas24h', '147a@258', 'HUONGMINH', 0,
//             '84976994876', 'Gas24h', 'bulksms',
//             'DH ADMIN TEST. Gia BB: 20,657 - Gia B12: 291,000 INDUSTRIAL001-Cong ty TNHH ',
//             0);
    CommonProcess::echoTest("CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4): ", CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_4));
    CommonProcess::echoTest("CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_6): ", CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_6));
    // Test username
    $fullName = "Phạm Trung Nguyên";
    $fullName1 = "Ngô Quang Phục";
    // Test generate username
    CommonProcess::echoTest("Username of '$fullName': ", Users::generateUsername($fullName));
    CommonProcess::echoTest("Username of '$fullName1': ", Users::generateUsername($fullName1));
    CommonProcess::echoTest("Username converted from '$fullName': ", CommonProcess::getUsernameFromFullName($fullName));
    CommonProcess::echoTest("Username converted from '$fullName1': ", CommonProcess::getUsernameFromFullName($fullName1));
    // Test compare date
    $date1 = "2018/03/23";
    $date2 = "2018-03-23 23:09:27";
    $date2 = CommonProcess::convertDateTime($date2, DomainConst::DATE_FORMAT_1, DomainConst::DATE_FORMAT_4);
    CommonProcess::echoTest("Compare date '$date1' - '$date2': ", DateTimeExt::compare($date1, $date2, ''));
    CommonProcess::echoTest("strtotime($date1): ", strtotime($date1));
    CommonProcess::echoTest("strtotime($date2): ", strtotime($date2));
    // Test DirectoryHandler
    CommonProcess::echoTest('Yii::app()->createAbsoluteUrl(DIRECTORY_SEPARATOR): ', Yii::app()->createAbsoluteUrl(DIRECTORY_SEPARATOR));
    CommonProcess::echoTest('Yii::app()->baseUrl: ', Yii::app()->baseUrl);
//    CommonProcess::echoTest('Create path from array: ', DirectoryHandler::createPath(array(
//        DirectoryHandler::getRootPath(),
//        'a',
//        'b',
//        'c'
//    )));
//    CommonProcess::echoTest('Yii root path: ', DirectoryHandler::getRootPath());
    CommonProcess::echoTest('Current date time: ', CommonProcess::getCurrentDateTime(DomainConst::DATE_FORMAT_3));
    $to = time();
    ScheduleEmail::logInfo($from, $to, __METHOD__, 5);
    ?>
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->

    <div class="group-btn" id="create_customer">
        <?php
            echo CHtml::link("Open dialog", '#', array(
                'style' => 'cursor: pointer;',
                'onclick' =>''
                . 'createPrintDialog();'
                . ' $("#dialog").dialog("open");'
                . ' return false;',
            ));
        ?>
    </div>
    <!-- Create new dialog -->
    <?php
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'    => 'dialog',
            'options' => array(
                'title' => "In phiếu thu",
                'autoOpen'  => false,
                'modal'     => true,
                'position'  => array(
                    'my'    => 'top',
                    'at'    => 'top',
                ),
                'width'     => 1300,
                'heigh'     => 670,
                'close'     => 'js:function() { }',
            ),
        ));
    ?>
    <div class="divForForm"></div>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
    <input type="button" onclick="window.print()"/>

<script type="text/javascript">
    /**
     * Create customer dialog
     * @returns {Boolean}
     */
    function createPrintDialog() {
        $("<link/>", {
            id: "form_ccs",
            rel: "stylesheet",
            type: "text/css",
            href: "<?php echo Yii::app()->theme->baseUrl . '/css/form.css'; ?>"
         }).appendTo("head");
        <?php
        echo CHtml::ajax(array(
            'url' => Yii::app()->createAbsoluteUrl('front/receptionist/printReceipt'),
            'data' => "js:$(this).serialize()",
            'type' => 'post',
            'dataType' => 'json',
            'success' => "function(data)
                    {
                        if (data.status == 'failure')
                        {
                            $('#dialog div.divForForm').html(data.div);
                                  // Here is the trick: on submit-> once again this function!
                            $('#dialog div.divForForm form').submit(createPrintDialog);
                        }
                        else
                        {
                            $('#dialog div.divForForm').html(data.div);
                            setTimeout(\"$('#dialog').dialog('close') \",1000);
                        }

                    } ",
        ))
        ?>;
        return false;
    }
</script>
