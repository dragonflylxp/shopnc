<?php
defined('Inshopec') or exit('Access Invalid!');
/**
 * 預存款功能公用
 */
$lang['predeposit_no_record']	 			= '沒有符合條件的記錄';
$lang['predeposit_unavailable']	 			= '系統未開啟預存款功能';
$lang['predeposit_parameter_error']			= '參數錯誤';
$lang['predeposit_record_error']			= '記錄資訊錯誤';
$lang['predeposit_userrecord_error']		= '會員資訊錯誤';
$lang['predeposit_payment']					= '支付方式';
$lang['predeposit_addtime']					= '創建時間';
$lang['predeposit_apptime']					= '申請時間';
$lang['predeposit_checktime']					= '審核時間';
$lang['predeposit_paytime']					= '付款時間';
$lang['predeposit_addtime_to']				= '至';
$lang['predeposit_trade_no']				= '交易號';
$lang['predeposit_adminremark']				= '管理員備註';
$lang['predeposit_recordstate']				= '記錄狀態';
$lang['predeposit_paystate']				= '狀態';
$lang['predeposit_backlist']				= '返回列表';
$lang['predeposit_pricetype']				= '預存款類型';
$lang['predeposit_pricetype_available']		= '可用金額';
$lang['predeposit_pricetype_freeze']		= '凍結金額';
$lang['predeposit_price']					= '金額';
$lang['predeposit_payment_error']			= '支付方式錯誤';
/**
 * 充值功能公用
 */
$lang['predeposit_rechargesn']					= '充值單號';
$lang['predeposit_rechargewaitpaying']			= '未支付';
$lang['predeposit_rechargepaysuccess']			= '已支付';
$lang['predeposit_rechargestate_auditing']		= '審核中';
$lang['predeposit_rechargestate_completed']		= '已完成';
$lang['predeposit_rechargestate_closed']		= '已關閉';
$lang['predeposit_recharge_price']				= '充值金額';
$lang['predeposit_recharge_huikuanname']		= '匯款人姓名';
$lang['predeposit_recharge_huikuanbank']		= '匯款銀行';
$lang['predeposit_recharge_huikuandate']		= '匯款日期';
$lang['predeposit_recharge_memberremark']		= '會員備註';
$lang['predeposit_recharge_success']			= '充值成功';
$lang['predeposit_recharge_fail']				= '充值失敗';
$lang['predeposit_recharge_pay']				= '支&nbsp;付';
$lang['predeposit_recharge_view']				= '查看詳單';
$lang['predeposit_recharge_paydesc']			= '預存款充值訂單';
$lang['predeposit_recharge_pay_offline']		= '待確認';
/**
 * 充值添加
 */
$lang['predeposit_recharge_add_pricenull_error']			= '請添加充值金額';
$lang['predeposit_recharge_add_pricemin_error']				= '充值金額為大於或者等於0.01的數字';
/**
 * 充值資訊刪除
 */
$lang['predeposit_recharge_del_success']		= '充值資訊刪除成功';
$lang['predeposit_recharge_del_fail']		= '充值資訊刪除失敗';
/**
 * 提現功能公用
 */
$lang['predeposit_cashsn']				= '申請單號';
$lang['predeposit_cashmanage']			= '提現管理';
$lang['predeposit_cashwaitpaying']		= '等待支付';
$lang['predeposit_cashpaysuccess']		= '支付成功';
$lang['predeposit_cashstate_auditing']	= '審核中';
$lang['predeposit_cashstate_completed']	= '已完成';
$lang['predeposit_cashstate_closed']		= '已關閉';
$lang['predeposit_cash_price']				= '提現金額';
$lang['predeposit_cash_shoukuanname']			= '開戶人姓名';
$lang['predeposit_cash_shoukuanbank']			= '收款銀行';
$lang['predeposit_cash_shoukuanaccount']		= '收款帳號';
$lang['predeposit_cash_shoukuanname_tip']	= '強烈建議優先填寫國有4大銀行(中國銀行、中國建設銀行、中國工商銀行和中國農業銀行)<br/>請填寫詳細的開戶銀行分行名稱，虛擬帳戶如支付寶、財付通填寫“支付寶”、“財付通”即可';
$lang['predeposit_cash_shoukuanaccount_tip']	= '銀行帳號或虛擬帳號(支付寶、財付通等帳號)';
$lang['predeposit_cash_shoukuanauser_tip']	= '收款帳號的開戶人姓名';
$lang['predeposit_cash_shortprice_error']		= '預存款金額不足';
$lang['predeposit_cash_price_tip']				= '當前可用金額';

$lang['predeposit_cash_availablereducedesc']	=  '會員申請提現減少預存款金額';
$lang['predeposit_cash_freezeadddesc']	=  '會員申請提現增加凍結預存款金額';
$lang['predeposit_cash_availableadddesc']	=  '會員刪除提現增加預存款金額';
$lang['predeposit_cash_freezereducedesc']	=  '會員刪除提現減少凍結預存款金額';

/**
 * 提現添加
 */
$lang['predeposit_cash_add_shoukuannamenull_error']		= '請填寫收款人姓名';
$lang['predeposit_cash_add_shoukuanbanknull_error']		= '請填寫收款銀行';
$lang['predeposit_cash_add_pricemin_error']				= '提現金額為大於或者等於0.01的數字';
$lang['predeposit_cash_add_enough_error']				= '帳戶餘額不足';
$lang['predeposit_cash_add_pricenull_error']			= '請填寫提現金額';
$lang['predeposit_cash_add_shoukuanaccountnull_error']	= '請填寫收款帳號';
$lang['predeposit_cash_add_success']					= '您的提現申請已成功提交，請等待系統處理';
$lang['predeposit_cash_add_fail']						= '提現資訊添加失敗';
/**
 * 提現資訊刪除
 */
$lang['predeposit_cash_del_success']	= '提現資訊刪除成功';
$lang['predeposit_cash_del_fail']		= '提現資訊刪除失敗';
/**
 * 支付介面
 */
$lang['predeposit_payment_pay_fail']		= '充值失敗';
$lang['predeposit_payment_pay_success']		= '充值成功，正在前往我的訂單';
$lang['predepositrechargedesc']	=  '充值';
/**
 * 出入明細 
 */
$lang['predeposit_log_stage'] 			= '類型';
$lang['predeposit_log_stage_recharge']	= '充值';
$lang['predeposit_log_stage_cash']		= '提現';
$lang['predeposit_log_stage_order']		= '消費';
$lang['predeposit_log_stage_artificial']= '手動修改';
$lang['predeposit_log_stage_system']	= '系統';
$lang['predeposit_log_stage_income']	= '收入';
$lang['predeposit_log_desc']			= '變更說明';
?>