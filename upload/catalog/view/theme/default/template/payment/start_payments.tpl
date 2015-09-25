<div class="buttons">
    <div class="pull-right">
        <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
    </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function () {
        $.ajax({
            url: 'index.php?route=payment/start_payments/send',
            type: 'post',
            data: $('.start_response :input'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-confirm').attr('disabled', true);
                $('#payment').before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
            },
            complete: function () {
                $('#button-confirm').attr('disabled', false);
                $('.attention').remove();
            },
            success: function (json) {
                if (json['error']) {
                    removePaymentToken();
                    $('input[name="payment_method"][value="start_payments"]').parent().append("<span class='text-danger'><br/>"+json['error']+"<span>");
                    $('#accordion > div:nth-last-child(2)').find('h4.panel-title>a').trigger('click');
                }
                if (json['success']) {
                    location = json['success'];
                }
            }
        });
    });
//--></script>