<HTML>
<HEAD><TITLE>アカウント新規登録</TITLE></HEAD>
<BODY>

<?php

require_once 'HTML/QuickForm.php';



//*****************************************************
//
//登録情報
//
//*****************************************************
$form = new HTML_QuickForm('myForm','POST','/../html_test2/index.html');

//include file
$pref="";
require("./select1.php");

////////////
//個人情報//
////////////
$form->addElement('header', null, '個人の情報');
$form->addElement('text', 'Name', '名前', 'size=15');
$form->addElement('password', 'Pass', 'パスワード', 'size=15');
$form->addElement('text', 'email1', 'メールアドレス', 'size=25');
$form->addElement('text', 'email2', 'メールアドレスもう一度', 'size=25');
$form->addElement('text', 'mail1', '携帯メールアドレス', 'size=25');
$form->addElement('text', 'mail2', '携帯メールアドレスもう一度', 'size=25');

$sex = array();
$sex[] =& $form->createElement("radio", "0", NULL, "未回答", "0");
$sex[] =& $form->createElement("radio", "0", NULL, "男性",   "1");
$sex[] =& $form->createElement("radio", "0", NULL, "女性",   "2");
$form->addGroup($sex,"sex","性別：");

$form->addElement('date', 'Date', '日付', 'size=25');
$form->addElement('select', 'prefecture','都道府県',$pref);

////////////
//障害情報//
////////////
$form->addElement('header', null, '障害情報');
$form->addElement('text', 'width', '通れる幅','cm','size=15');
$form->addElement('text', 'rank1', '乗り越えられる段差','size=15');
$form->addElement('text','rank2', '乗り越えられる段差(付き添いあり)','size=15');

$group[] =& HTML_QuickForm::createElement('advcheckbox', "0",NULL,"引き戸","0");
$group[] =& HTML_QuickForm::createElement('advcheckbox', "0",NULL,"開き戸","1");
$group[] =& HTML_QuickForm::createElement('advcheckbox', "0",NULL,"自動ドア","2");
$form->addGroup($group, 'door', 'ドア:', ',&nbsp;');

$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"障害者用エレベーター","0");
$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"エレベーター","1");
$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"なし","2");
$form->addGroup($group1, 'elevater', 'エレベーター:', ',&nbsp;');

$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"手摺あれば上れる","0");
$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"手摺なくても上れる","1");
$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"上れない","2");
$form->addGroup($group2, 'step', '階段:', ',&nbsp;');

$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"障害者用トイレあり","0");
$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"トイレあり","1");
$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"なし","2");
$form->addGroup($group3, 'toilet', 'トイレ:', ',&nbsp;');

$group4[] =& HTML_QuickForm::createElement('radio', "0",NULL,"あり","0");
$group4[] =& HTML_QuickForm::createElement('radio', "0",NULL,"なし","1");
$form->addGroup($group4, 'baby', 'ベビー関係:', ',&nbsp;');


//////////////
//不備の確認//
//////////////
$form->addElement('submit', null, 'home');
$form->addRule('Name', '名前を入力してください', 'required');
$form->addRule('Address', '住所を入力してください', 'required');
$form->addRule('Address', '住所は3文字以上と定められています', 'minlength', 3);
$form->addRule('Mail1', '正しいメールアドレスを入力してください', 'email');
$form->addRule(array('Mail1', 'Mail2'), 'メールアドレスが一致しません', 'compare', '==');

$form->setRequiredNote('<span style="color: #ff0000;">*</span>は必須項目です');

//////////////////
//バリデーション//
//////////////////
if ($form->validate())
{
	if ($form->getSubmitValue('Status') == 'confirm')
	{
		echo '<h2>了解：' . $form->exportValue('Name') . 'に発送します</h2>';
		echo '発送先住所：　' . $form->exportValue('Address') . '<BR>';
		echo '連絡先：　' . $form->exportValue('Mail1') . '<BR>';
	}
	else
	{
		$form->addElement('hidden', 'Status', 'confirm');
		// ↑<input type="hidden" name="Status" value="confirm" />
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
