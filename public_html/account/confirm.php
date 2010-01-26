<!--
************************************************
**   認証画面
**
**                               by ohno
**                               2010/01/
************************************************
-->

<HTML>
<HEAD><TITLE>認証画面</TITLE></HEAD>
<BODY>


<DIV align="center">
<DIV align="left">
               <A Href="/../html_test2/index.html" Target="_blank"> 
               <h1><img src="/resource/image/logo_small.png" Border="0" with="200" height="50"
                        alt="バリアフリーマップ検索" /></h1>
                </td><td style="text-align: right;">
</DIV>
<?php

require_once 'HTML/QuickForm.php';

 
//*****************************************************
//
//登録情報
//
//*****************************************************
$form = new HTML_QuickForm('myForm','POST','confirm2.php');

//include file
$pref="";
require("./select1.php");

////////////
//個人情報//
////////////
$form->addElement('header', null, '個人の情報');
$form->addElement('text', 'username', '名前', 'size=15');
$form->addElement('password','password','パスワード','size=15');
$form->addElement('text', 'email', 'メールアドレス', 'size=25');
$form->addElement('text', 'email_2', 'メールアドレスもう一度', 'size=25');
$form->addElement('text', 'mobilemail', '携帯メールアドレス', 'size=25');
$form->addElement('text', 'mobilemail_2', '携帯メールアドレスもう一度', 'size=25');

$gender = array();
$gender[] =& $form->createElement("radio", "0", NULL, "未回答", "N");
$gender[] =& $form->createElement("radio", "0", NULL, "男性",   "M");
$gender[] =& $form->createElement("radio", "0", NULL, "女性",   "F");
$form->addGroup($gender,"gender","性別：");


$form->addElement('date', 'date' ,'生年月日', array('language' => 'en', 'minYear' => 1920, 'maxYear' => 2007, 'format' => 'Y m d'));

$form->addElement('select', 'prefecture','都道府県',$pref);

////////////
//障害情報//
////////////
$form->addElement('header', null, '障害情報');
$form->addElement('text', 'width', '通れる幅','cm','size=15');
$form->addElement('text', 'rank1', '乗り越えられる段差','size=15');
$form->addElement('text','rank2', '乗り越えられる段差(付き添いあり)','size=15');

$group[] =& HTML_QuickForm::createElement('checkbox', "0",NULL,"引き戸");
$group[] =& HTML_QuickForm::createElement('checkbox', "1",NULL,"開き戸");
$group[] =& HTML_QuickForm::createElement('checkbox', "2",NULL,"自動ドア");
$form->addGroup($group, 'door', 'ドア:', ',&nbsp;');

$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"障害者用エレベーター","large-ev");
$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"エレベーター","normal-ev");
$group1[] =& HTML_QuickForm::createElement('radio', "0",NULL,"なし","without-ev");
$form->addGroup($group1, 'elevator', 'エレベーター:', ',&nbsp;');

$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"手摺あれば上れる","with-banister");
$group2[] =& HTML_QuickForm::createElement('radio', "0",NULL,"上れない","cannot-climb");
$form->addGroup($group2, 'step', '階段:', ',&nbsp;');

$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"障害者用トイレあり","disabled-toilet");
$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"トイレあり","normal-toilet");
$group3[] =& HTML_QuickForm::createElement('radio', "0",NULL,"なし","without-toilet");
$form->addGroup($group3, 'toilet', 'トイレ:', ',&nbsp;');

$group4[] =& HTML_QuickForm::createElement('radio', "0",NULL,"あり","with-baby");
$group4[] =& HTML_QuickForm::createElement('radio', "0",NULL,"なし","without-baby");
$form->addGroup($group4, 'baby', 'ベビー関係:', ',&nbsp;');

$form->addElement('submit', null, '送信');

//////////////
//不備の確認//
//////////////
$form->addRule('username', '名前を入力してください', 'required');
$form->addRule('password', 'パスワードを入力してください', 'required');
//$form->addRule('Address', '住所は3文字以上と定められています', 'minlength', 3);
$form->addRule('email', '正しいメールアドレスを入力してください', 'required');
$form->addRule('email_2', '正しいメールアドレスを入力してください', 'required');
$form->addRule(array('email', 'email_2'), 'メールアドレスが一致しません', 'compare', '==');
$form->addRule(array('mobilemail', 'mobilemail_2'), '携帯のメールアドレスが一致しません', 'compare', '==');

$form->setRequiredNote('<span style="color: #ff0000;">*</span>は必須項目です');

//////////////////
//バリデーション//
//////////////////
if($form->validate())
{
//*************************
//password 受け渡し
//*************************
$pass = $_POST['password'];
$form->addElement('hidden','password_x',"$pass");

		$form->addElement('hidden', 'Status', 'confirm');
		//↑<input type="hidden" name="Status" value="confirm" />
		$form->freeze();
}
if ($form->getSubmitValue('Status') != 'confirm')
{
	$form->display();
}
?>
<form name="form1" action="confirm2.php" method="POST">
      <input type = "hidden" name = "password" value = "$_POST['password']">
</form>
<!--HOME link-->
<p><a href="/../html_test2/index.html?no=1">ホーム</a></p>
<A Href="javascript:history.go(-1)">1つ前に戻る</A>

</DIV>
</BODY>
</HTML>


