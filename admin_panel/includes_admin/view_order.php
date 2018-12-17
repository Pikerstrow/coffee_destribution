<div id="modal-wrapper" class="modal">

    <div class=" col-xs-12 col-sm-6 col-sm-offset-3 modal-content animate">
        <div id="close_view_order" class="text-center">
            <button style="padding:5px;" class="btn btn-danger btn-block"><i class="fas fa-times fa-lg"></i></button>
        </div>
        <div id="order-details-div" style="width:90%; margin: 0 auto; padding: 20px 0px">
<!--            <img src="images/wait.gif" id="img_preload" style="display:none; margin: 100px auto;">-->
        </div>
    </div>
</div>

<script>
    /*FOR OPENING AND CLOSING MODAL WINDOW WITH ORDER DETAILS*/
    $(document).ready(function(){
        $('.order-details').click(function(event){
            event.preventDefault();
            $('div.modal').css('display', 'block');
        });
        $('#close_view_order').click(function(e){
            e.preventDefault();
            $('div.modal').css('display', 'none');
        });
    });
</script>
<script>
    /*ORDER DETAILS FROM ORDER HISTORY*/
    $(document).ready(function(){
        var viewOrderLink;
        var viewOrderLinkSplitted;
        var orderId;

        $('a.order-details').click(function(e){
            e.preventDefault();

            $('#order-details-div').html('<div class="text-center" style="width:100%; padding-top:100px; padding-bottom:100px;"><img src="images/wait.gif" id="img_preload" style="display:none;"></div>');

            viewOrderLink = $(this).prop('href');
            viewOrderLinkSplitted = viewOrderLink.split('=');
            orderId = viewOrderLinkSplitted[viewOrderLinkSplitted.length - 1];

            $.ajax({
                url: "includes_admin/ajax_order_detail.php",
                data: {
                    order_id: orderId
                },
                type: "post",
                beforeSend: function(){
                    $('#img_preload').show();
                },
                complete: function(){
                    $('#img_preload').hide();
                },
                success: function(data){
                    if(!data.error){
                        $('#order-details-div').html(data);
                        //console.log(data);
                    }
                }
            })
        });
    });
</script>

