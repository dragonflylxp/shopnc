<?php defined('Inshopec') or exit('Access Invalid!'); ?>

<div class="ncc-main">
    <div class="ncc-title">
        <h3><?php echo $lang['cart_index_payment']; ?></h3>
        <h5>订单详情内容可通过查看<a href="index.php?con=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
    </div>
    <form action="index.php?con=payment&fun=real_order" method="POST" id="buy_form">
        <input type="hidden" name="pay_sn" value="<?php echo $output['pay_info']['pay_sn']; ?>">
        <input type="hidden" id="payment_code" name="payment_code" value="">
        <input type="hidden" value="" name="password_callback" id="password_callback">

        <div class="ncc-receipt-info">
            <div class="ncc-receipt-info-title">
                <h3>
                    <?php echo $output['pay']['order_remind']; ?>
                    <?php echo $output['pay']['pay_amount_online'] > 0 ? "应付金额：<strong>" . ncPriceFormat($output['pay']['pay_amount_online']) . "</strong>元" : null; ?>
                </h3>
            </div>
            <table class="ncc-table-style">
                <thead>
                <tr>
                    <th class="w50"></th>
                    <th class="w200 tl">订单号</th>
                    <th class="tl w150">支付方式</th>
                    <th class="tl">金额(元)</th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($output['order_list']) > 1) { ?>
                    <tr>
                        <th colspan="20">由于您的商品由不同商家发出，此单将分为<?php echo count($output['order_list']); ?>个不同子订单配送！</th>
                    </tr>
                <?php } ?>
                <?php foreach ($output['order_list'] as $key => $order_info) { ?>
                    <tr>
                        <td></td>
                        <td class="tl"><?php echo $order_info['order_sn']; ?></td>
                        <td class="tl"><?php echo $order_info['payment_type']; ?></td>
                        <td class="tl"><?php echo $order_info['order_amount']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <!-- S 预存款 & 充值卡 -->
            <dl class="ncc-pd-pay">
                <?php if (!empty($output['pay']['payd_pd_amount']) || !empty($output['pay']['payd_rcb_amount'])) { ?>
                    <dd>您已选择
                        <?php echo !empty($output['pay']['payd_rcb_amount']) ? "使用充值卡支付<em>" . ncPriceFormat($output['pay']['payd_rcb_amount']) . "</em>元；" : null; ?>
                        <?php echo !empty($output['pay']['payd_pd_amount']) ? "使用预存款支付<em>" . ncPriceFormat($output['pay']['payd_pd_amount']) . "</em>元；" : null; ?>
                        还需在线支付 <strong
                            id="api_pay_amount"><?php echo ncPriceFormat($output['pay']['payd_diff_amount']); ?></strong>元。
                    </dd>
                <?php } ?>
                <?php if ($output['pay']['if_show_pdrcb_select']) { ?>
        <dt>使用余额支付</dt>
            <!--兑换券支付-->
            <?php if ($output['order_list'][0]['payment_code'] =='dhq') { ?>
                <dd>
                    <label><input type="checkbox" class="checkbox" value="3" name="exchange_card">
                        使用兑换券支付</label>
                </dd>
            <?php } ?>
            <!--兑换券支付-->
       <?php if ($output['order_list'][0]['payment_code'] !='dhq') { ?>
            <dd>
                <label><input type="checkbox" class="checkbox" value="1" name="rcb_pay" >
                    使用充值卡支付</label> （可用余额：<em><?php echo ncPriceFormat($output['pay']['member_rcb']); ?></em>元）
            </dd>
            <dd>
                <label><input type="checkbox" class="checkbox" value="1" name="pd_pay">
                    使用预存款支付</label> （可用余额：<em><?php echo ncPriceFormat($output['pay']['member_pd']); ?></em>元）
            </dd>
            <dd>（同时勾选时，系统将优先使用充值卡，不足时扣除预存款，目前还需在线支付 <strong id="api_pay_amount">
                    <?php echo ncPriceFormat($output['pay']['pay_amount_online']); ?></strong>元。）余额不足？<a
                    href="<?php echo urlMember('predeposit', 'pd_log_list'); ?>" class="predeposit">马上充值</a>
            </dd>
            <dd id="pd_password" style="display: none">请输入支付密码
                <input type="password" value="" name="password" id="pay-password" maxlength="35"
                       autocomplete="off">
                <a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet" id="pd_pay_submit"><i
                        class="icon-shield"></i>确认支付</a>
                <?php if (!$output['pay']['member_paypwd']) { ?>
                    还未设置支付密码，<a
                        href="<?php echo urlMember('member_security', 'auth', array('type' => 'modify_paypwd')); ?>"
                        target="_blank">马上设置</a>
                <?php } ?>

            </dd>
            <?php } ?>

        </dd>
          <dd id="exchange_card_password" style="display: none">请输入兑换券卡号
            <input type="text" value="" name="exchange_card_submit" id="pay_password_submit" maxlength="35" autocomplete="off">
            <a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet" id="pd_pay_submit_submit"><i class="icon-shield"></i><span id="icon_btn">确认兑换</span></a>
              <a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet" id="pd_pay_submit_submit"><span id="dhq_btn">我的兑换券</span></a>
              <span style="color: red;height: 18px;line-height: 18px;display: inline-block;margin: 4px;font-weight: 800;font-size: 12px">(注意 ： 使用兑换券购买的商品取消订单或退货时，不退还该使用的兑换券。)</span>
              <!--兑换券列表-->
              <table class="ncc-table-style" style="display:none" id="dhq_list">

                  <thead>
                  <?php if(0 == count($output['dhq_list'])){  ?>
                      <tr style="height:50px;">
                          <th class="w150"></th>
                          <th colspan="3" class="w150">您暂无可用兑换券。</th>
                          <th class="w100"></th>

                      </tr>
                  <?php }else{ ?>
                  <tr style="height: 32px">
                      <th class="w50"></th>
                      <th class="w200 tl">兑换号</th>
                      <th class="tl w150">金额(元)</th>
                      <th class="tl">操作</th>
                      <th class="w50"></th>
                  </tr>
                  </thead>
                  <tbody>

                  <style>
                      .dhq_link{
                          display: block;
                          width: 32px;
                          height: 26px;
                          line-height: 26px;
                          text-align: center;
                          color: white;
                          background-color: #00d6b2;
                          border-radius: 5px;
                          margin: 2px auto;
                      }
                      .dhq_link:hover{
                          background-color: #02b597;
                          color: white;
                      }
                  </style>
                  <?php foreach ($output['dhq_list'] as  $dhq) { ?>
                      <tr style="height: 32px">
                          <td></td>
                          <td class="tl"><?php echo $dhq['sn']; ?></td>
                          <td class="tl"><?php echo $dhq['denomination'];?></td>
                          <td class="tl"><a href="javascript:;" class="dhq_link" data-id="<?php echo $dhq['sn']; ?>">使用</a></td>
                          <td></td>
                      </tr>
                  <?php } } ?>

                  <?php if(!empty($output['dhq_list'])){;?>
                  <!--                  使用兑换卷增加提示信息-->
                    <tr>
                        <td colspan="5" rowspan="2" style="color: red;text-align: center;font-weight: 800;font-size: 12px"><p style="padding: 20px">(注意 ： 使用兑换券购买的商品取消订单或退货时，不退还该使用的兑换券。)</p></td>
                    </tr>
                    <tr></tr>

                  <!--                  使用兑换卷增加提示信息-->
                  <?php };?>
                  </tbody>

              </table>
              <!--end-->
        </dd>

      <?php } ?>

            </dl>
        </div>
        <?php if ($output['order_list'][0]['payment_code'] != 'dhq') {; ?>
            <?php if ($output['pay']['pay_amount_online'] > 0) { ?>
                <div class="ncc-receipt-info" id="pay_selet">
                    <div class="ncc-receipt-info-title">
                        <h3>选择支付方式</h3>
                    </div>
                    <ul class="ncc-payment-list">
                        <?php foreach ($output['payment_list'] as $val) { ?>
                            <li payment_code="<?php echo $val['payment_code']; ?>">
                                <label for="pay_<?php echo $val['payment_code']; ?>">
                                    <i></i>

                                    <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"><img
                                            src="<?php echo SHOP_TEMPLATES_URL ?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif"/>
                                    </div>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if ($output['pay']['pay_amount_online'] > 0) { ?>
                <div class="ncc-bottom"><a href="javascript:void(0);" id="next_button" class="pay-btn"><i
                            class="icon-shield"></i>确认支付</a></div>
            <?php } ?>
        <?php } ?>
    </form>
</div>
<script type="text/javascript">
    var pay_amount_online = <?php echo $output['pay']['pay_amount_online'];?>;
    var member_rcb = <?php echo $output['pay']['member_rcb'];?>;
    var member_pd = <?php echo $output['pay']['member_pd'];?>;
    var pay_diff_amount = <?php echo $output['pay']['pay_amount_online'] ? $output['pay']['pay_amount_online'] : $output['pay']['payd_diff_amount'];?>;

    //判断选择支付方式是否为银行汇款
    function sub_offbank(flag) {
        if (flag) {
            $('#payment_code').val("offbank");
            $('#buy_form').submit();
        } else {
            $('#buy_form').submit();
        }
    }

    $(function () {
        $('.ncc-payment-list > li').on('click', function () {
            $('.ncc-payment-list > li').removeClass('using');
            if ($('#payment_code').val() != $(this).attr('payment_code')) {
                $('#payment_code').val($(this).attr('payment_code'));
                $(this).addClass('using');
            } else {
                $('#payment_code').val('');
            }
        });
        $('#next_button').on('click', function () {
            if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
                showDialog('使用充值卡/预存款支付，需输入支付密码并确认  ', 'error', '', '', '', '', '', '', '', 2);
                return;
            }
            if ($('#payment_code').val() == '' && parseFloat($('#api_pay_amount').html()) > 0) {
                showDialog('请选择一种在线支付方式', 'error', '', '', '', '', '', '', '', 2);
                return;
            }
            //增加银行汇款方式
            if (pay_diff_amount >= 20000 && $('#payment_code').val() != 'offbank') {
                var html = "订单金额过大，推荐使用银行汇款方式支付" +
                    "<p style='margin:5px 0px;'><span onclick='sub_offbank(1);' style='cursor:pointer;margin-right:24px;padding:2px;height:20px;background:#27a9e3;color:#fff;line-height:20px;border-radius:3px;display:inline-block;'>银行汇款</span><span onclick='sub_offbank(0);' style='cursor:pointer;margin-right:24px;padding:2px;height:20px;background:#0094DE;color:#fff;line-height:20px;border-radius:3px;display:inline-block;'>原支付方式</span></p>";
                showDialog(html, 'notice', '', '', '', '', '', '', '', 30);
            } else {
                $('#buy_form').submit();
            }
        });

        <?php if ($output['pay']['if_show_pdrcb_select']) { ?>
        function showPaySubmit() {
            if ($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) {
                $('#pay-password').val('');
                $('#password_callback').val('');
                $('#pd_password').show();
            } else {
                $('#pd_password').hide();
            }
            var _diff_amount = pay_diff_amount;
            if ($('input[name="rcb_pay"]').attr('checked')) {
                _diff_amount -= member_rcb;
            }
            _diff_amount = parseFloat(_diff_amount.toFixed(2));
            if ($('input[name="pd_pay"]').attr('checked')) {
                _diff_amount -= member_pd;
            }
            _diff_amount = parseFloat(_diff_amount.toFixed(2));
            if (_diff_amount < 0) {
                _diff_amount = 0;
            }
            $('#api_pay_amount').html(_diff_amount.toFixed(2));
        }

        $('#pd_pay_submit').on('click', function () {
            if ($('#pay-password').val() == '') {
                showDialog('请输入支付密码', 'error', '', '', '', '', '', '', '', 2);
                return false;
            }
            $('#password_callback').val('');
            $.get("index.php?con=buy&fun=check_pd_pwd", {'password': $('#pay-password').val()}, function (data) {
                if (data == '1') {
                    $('#password_callback').val('1');
                    $('#pd_password').hide();
                } else {
                    $('#pay-password').val('');
                    showDialog('支付密码错误', 'error', '', '', '', '', '', '', '', 2);
                }
            });
        });

        $('input[name="rcb_pay"]').on('change', function () {
            showPaySubmit();
            if ($(this).attr('checked') && !$('input[name="pd_pay"]').attr('checked')) {
                if (member_rcb >= pay_amount_online) {
                    $('input[name="pd_pay"]').attr('checked', false).attr('disabled', true);
                }
            } else {
                $('input[name="pd_pay"]').attr('disabled', false);
            }
        });

        $('input[name="pd_pay"]').on('change', function () {
            showPaySubmit();
            if ($(this).attr('checked') && !$('input[name="rcb_pay"]').attr('checked')) {
                if (member_pd >= pay_amount_online) {
                    $('input[name="rcb_pay"]').attr('checked', false).attr('disabled', true);
                }
            } else {
                $('input[name="rcb_pay"]').attr('disabled', false);
            }
        });
        $('input[name="exchange_card"]').on('change', function () {
            if ($(this).is(':checked') == true) {
                $('input[name="rcb_pay"]').attr("disabled", true);
                $('input[name="rcb_pay"]').removeAttr("checked");
                $('input[name="pd_pay"]').removeAttr("checked");
                $('#pd_password').hide();
                $('input[name="pd_pay"]').attr("disabled", true);
                $("#exchange_card_password").show();
                $("#pay_selet").hide();
                $(".ncc-bottom").hide();
            } else {
                $('input[name="rcb_pay"]').attr("disabled", false);
                $('input[name="pd_pay"]').attr("disabled", false);
                $("#exchange_card_password").hide();
                $("#pay_selet").show();
                $(".ncc-bottom").show();
            }
        });

        $("#pd_pay_submit_submit").on("click", function () {

            if ($("#pay_password_submit").val() == "") {
                showDialog('请输入兑换券卡号', 'error', '', '', '', '', '', '', '', 2);
            } else {
                //$('#buy_form').submit();

                $.ajax({
                    type: "POST",
                    url: $("form").attr('action'),
                    data: $("form").serializeArray(),
                    dataType: 'json',
                    beforeSend: function () {
                        $("#icon_btn").html("兑换中");
                    },
                    success: function (data) {
                        if (data.status == 0) {
                            $("#icon_btn").html("确认兑换");
                            showDialog(data.info, 'error', '', '', '', '', '', '', '', 2);
                            return false;
                        } else {
                            window.location.href = data.url;
                        }
                    }
                })
            }
        })

        $("#dhq_btn").click(function () {
            //dhq_list
            var _html = $("#dhq_list").html();
            var d = DialogManager.create('dhq_list');
            d.setTitle('我的兑换券');
            d.setContents(_html);
            d.show('center', 1);
        })

        $(".dhq_link").live("click", function () {
            var sn = $(this).attr("data-id");
            $("#pay_password_submit").val(sn);
            var d = DialogManager.create('dhq_list');
            d.close();
        })


        <?php } ?>
    });
</script>