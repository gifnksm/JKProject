<HTML>
<HEAD><TITLE>�A�J�E���g�V�K�o�^</TITLE></HEAD>
<BODY>

<?php

require_once 'HTML/QuickForm.php';



//*****************************************************
//
//�o�^���
//
//*****************************************************
$form = new HTML_QuickForm('myForm','POST','/../html_test2/index.html');

//include file
$pref="";
require("./select1.php");

////////////
//�l���//
////////////
$form->addElement('header', null, '�l�̏��');
$form->addElement('text', 'Name', '���O', 'size=15');
$form->addElement('password', 'Pass', '�p�X���[�h', 'size=15');
$form->addElement('text', 'email1', '���[���A�h���X', 'size=25');
$form->addElement('text', 'email2', '���[���A�h���X������x', 'size=25');
$form->addElement('text', 'mail1', '�g�у��[���A�h���X', 'size=25');
$form->addElement('text', 'mail2', '�g�у��[���A�h���X������x', 'size=25');

$sex = array();
$sex[] =& $form->createElement("radio", "0", NULL, "����", "0");
$sex[] =& $form->createElement("radio", "0", NULL, "�j��",   "1");
$sex[] =& $form->createElement("radio", "0", NULL, "����",   "2");
$form->addGroup($sex,"sex","���ʁF");

$form->addElement('date', 'Date', '���t', 'size=25');
$form->addElement('select', 'prefecture','�s���{��',$pref);

////////////
//��Q���//
////////////
$form->addElement('header', null, '��Q���');
$form->addElement('text', 'width', '�ʂ�镝','cm','size=15');
$form->addElement('text', 'rank1', '���z������i��','size=15');
$form->addElement('text','rank2', '���z������i��(�t���Y������)','size=15');

$group[] =& HTML_QuickForm::createElement('advcheckbox', "0",NULL,"������","0");
$group[] =& HTML_QuickForm::createElement('advcheckbox', "0",NULL,"�J����","1");
$group[] =& HTML_QuickForm::createElement('advcheckbox', "0",NULL,"�����h�A","2");
$form->addGroup($group, 'door', '�h�A:', ',&nbsp;');

$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"��Q�җp�G���x�[�^�[","0");
$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"�G���x�[�^�[","1");
$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"�Ȃ�","2");
$form->addGroup($group1, 'elevater', '�G���x�[�^�[:', ',&nbsp;');

$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"�萠����Ώ���","0");
$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"�萠�Ȃ��Ă�����","1");
$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"���Ȃ�","2");
$form->addGroup($group2, 'step', '�K�i:', ',&nbsp;');

$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"��Q�җp�g�C������","0");
$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"�g�C������","1");
$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"�Ȃ�","2");
$form->addGroup($group3, 'toilet', '�g�C��:', ',&nbsp;');

$group4[] =& HTML_QuickForm::createElement('radio', "0",NULL,"����","0");
$group4[] =& HTML_QuickForm::createElement('radio', "0",NULL,"�Ȃ�","1");
$form->addGroup($group4, 'baby', '�x�r�[�֌W:', ',&nbsp;');


//////////////
//�s���̊m�F//
//////////////
$form->addElement('submit', null, 'home');
$form->addRule('Name', '���O����͂��Ă�������', 'required');
$form->addRule('Address', '�Z������͂��Ă�������', 'required');
$form->addRule('Address', '�Z����3�����ȏ�ƒ�߂��Ă��܂�', 'minlength', 3);
$form->addRule('Mail1', '���������[���A�h���X����͂��Ă�������', 'email');
$form->addRule(array('Mail1', 'Mail2'), '���[���A�h���X����v���܂���', 'compare', '==');

$form->setRequiredNote('<span style="color: #ff0000;">*</span>�͕K�{���ڂł�');

//////////////////
//�o���f�[�V����//
//////////////////
if ($form->validate())
{
	if ($form->getSubmitValue('Status') == 'confirm')
	{
		echo '<h2>�����F' . $form->exportValue('Name') . '�ɔ������܂�</h2>';
		echo '������Z���F�@' . $form->exportValue('Address') . '<BR>';
		echo '�A����F�@' . $form->exportValue('Mail1') . '<BR>';
	}
	else
	{
		$form->addElement('hidden', 'Status', 'confirm');
		// ��<input type="hidden" name="Status" value="confirm" />
		$form->freeze();
	}
}
if ($form->getSubmitValue('Status') != 'confirm')
{
	$form->display();
}

?>
<!--HOME link-->
<p><a href="/../html_test2/index.html?no=1">HOME</a></p>


</BODY>
</HTML>
