
<?php
delete_clients();
?>
<?php include_once "includes_admin/delete_modal.php"; ?>
<div class="col-xs-12 ">
    <h2 class="h2-panel">
        <span>Перелік клієнтів</span>
    </h2>
    <hr>


    <div class="table-responsive" id="table_with_goods">
        <table class="table table-bordered table-admin table-products table-orders">
            <thead>
                <tr>
                    <th style="vertical-align: middle">ID</th>
                    <th style="vertical-align: middle;">Назва</th>
                    <th style="vertical-align: middle;">Заклад</th>
                    <th style="vertical-align: middle">Конт. особа </th>
                    <th style="vertical-align: middle;">Телефон </th>
                    <th style="vertical-align: middle;">Email </th>
                    <th style="vertical-align: middle;">Знижка, % </th>
                    <th colspan="3" style="text-align: center; vertical-align: middle;" >Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "SELECT * FROM `my_coffee_clients` WHERE client_status = 1 AND client_deleted != 'true'";

                    $result = mysqli_query ($link, $query);
                    check_the_query($result);
                    
                    if (mysqli_num_rows($result) < 1) {
                        echo "<td colspan='8' class='text-center'><h3 style='margin:0;'>На даний момент зареєстровані клієнти відсутні.</h3></td>";
                    } else { 
                        while ($row = mysqli_fetch_assoc ( $result )) {
                ?>
                <tr class="cl-list-tr">
                    <td style="vertical-align: middle;"><?echo $row['client_id'] ?></td>
                    <td style="vertical-align: middle;"><?echo $row['client_name'] ?></td>
                    <td style="vertical-align: middle;"><?echo $row['client_establishment'] ?></td>
                    <td style="vertical-align: middle;"><?echo $row['client_person_name'] ?></td>
                    <td style="vertical-align: middle;"><?echo $row['client_phone_number'] ?></td>
                    <td style="vertical-align: middle;"><?echo $row['client_email'] ?></td>
                    <td style="vertical-align: middle;" class="client-disc-size"><?echo !empty($row['client_discont']) ? $row['client_discont'] : '0.00' ?></td>
                    <td class="text-center dialog-container" style="position: relative">

                        <!-- Modal -->
                        <div id="myModal<?php echo "_" . $row['client_id'] ?>" class="modal modal-sm fade " role="dialog">
                            <div class="modal-dialog modal-sm">
                                <!-- Modal content-->
                                <div class="modal-content ">
                                    <div class="modal-header text-left" style="position:relative;">
                                        <button type="button" class="btn btn-danger btn-sm button-close-modal" class="close" data-dismiss="modal" style="position:absolute;top:19%;right:3%"><i class="fas fa-times"></i></button>
                                        <h4 class="modal-title my-modal-title">Встановлення знижки</h4>
                                    </div>

                                    <div class="modal-body modal-set-discount">
                                        <h3 style="margin-top:0">Клієнт: <?php echo $row['client_name'] ?></h3>
                                        <form method="post" action="" class="set-disc-form">
                                            <div class="form-group">
                                                <span class="er-span bg-danger" style="color:red" ></span>
                                                <div class="input-group">
                                                   <span class="input-group-addon">%</span>
                                                   <input type="text" class="form-control" name="client_discount"
                                                          placeholder="10" required='required' maxlength="4" >
                                                </div>
                                                <hr>
                                                   <button class="btn btn-block btn-success btn-set-discount"
                                                            name="subm_discount_setting" data-id="<?php echo $row['client_id'] ?>">
                                                                 <i class="far fa-check-circle fa-lg"></i> Встановити
                                                   </button>

                                                <hr>
                                                <div class="row">
                                                    <div class="col-xs-12 text-left my-notice-modal">
                                                        <p><b>Важливо: </b> Дробні числа, повинні вводитися через крапку, наприклад, 2.5</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <button style="padding: 5px 10px;" data-toggle="modal" data-target="#myModal<?php echo "_" . $row['client_id'] ?>" class='btn btn-primary btn-xs' >
                            <i class="fas fa-percent fa-lg"></i>
                        </button>
                    </td>
                    <td class="text-center">
                        <a style="padding: 5px 10px;" class='btn btn-warning btn-xs' href="clients.php?route=edit_client&client_id=<?php echo $row['client_id']; ?>&edit_client=true&token=<?php echo session_id(); ?>">
                            <i class="fas fa-user-edit fa-lg"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <a style="padding: 5px 10px;" class='btn btn-danger btn-xs delete-cl-link' data-token="<?php echo session_id(); ?>" rel="<?php echo $row['client_id']; ?>" href="clients.php?delete=true&token=<?php echo session_id(); ?>&deleteCurrent=true&client_id=<?php echo $row['client_id']; ?>">
                            <i class="fas fa-user-minus fa-lg"></i>
                        </a>
                    </td>

                </tr>
                <?php
                        } // end of while 
                    }//end of else
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination for table with products -->
<script>

    $(document).ready(function () {
        $('#table_with_goods').after("<div id='table_nav'><button class='a-prev btn'><i class=\"fas fa-backward\"></i> </button> <button class='a-next btn'><i class=\"fas fa-forward\"></i> </button> <span class='pages-descr'>Сторінка <span class='page-num'> </span> із <span class='total_pages'> </span></span></div>");

        $('button.a-prev').prop('disabled', true);
            var rows_to_show = 10;
            var total_rows_quantity = $('#table_with_goods tbody').find('tr').length;
            var pages_quantity = Math.ceil(total_rows_quantity / rows_to_show);

            if(pages_quantity == 1){
                $('button.a-next').prop('disabled', true);
            }

        $('#table_with_goods tbody').find('tr').hide();
        $('#table_with_goods tbody').find('tr').slice(0, rows_to_show).show();

        var current_page = 0;

        $('#table_nav .a-next').bind('click', function () {
            $('button.a-prev').prop('disabled', false);
            if(current_page < pages_quantity-1){
                current_page++;
            }
            if (current_page == pages_quantity-1) {
                $(this).prop('disabled', true);
            }

            var start = current_page * rows_to_show;
            var end = start + rows_to_show;

            $('#table_with_goods tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);
            $('span.page-num').text(current_page+1);
        });

        $('#table_nav .a-prev').bind('click', function () {
            $('button.a-next').prop('disabled', false);

            if(current_page > 0){
                current_page--;
            }
            if (current_page == 0) {
                $(this).prop('disabled', true);
            }

            var start = current_page * rows_to_show;
            var end = start + rows_to_show;

            $('#table_with_goods tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);
            $('span.page-num').text(current_page+1);
        });

        $('span.page-num').text(current_page+1);
        $('span.total_pages').text(pages_quantity);

    });

</script>
<script>
    $(document).ready(function(){
        $('.delete-cl-link').click(function(event){

            event.preventDefault();
            var id = $(this).attr('rel');
            var token = $(this).data('token');
            var delete_url = "clients.php?delete=true&token="+token+"&deleteCurrent=true&client_id="+id;

            $('#myModal').modal('show');

            $('.modal_delete_link').attr('href', delete_url);

        });
    });
</script>