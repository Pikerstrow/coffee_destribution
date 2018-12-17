
<div class="col-xs-12 ">
    <h2 class="h2-panel">
        <span>Заявки на реєстрацію</span>
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
                    <th colspan="2" style="text-align: center; vertical-align: middle;" >Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "SELECT * FROM `my_coffee_clients` WHERE client_status = 0 AND client_deleted != 'true'";

                    $result = mysqli_query ($link, $query);
                    check_the_query($result);
                    
                    if (mysqli_num_rows($result) < 1) {
                        echo "<td colspan='8' class='text-center'><h3 style='margin:0;'>На даний момент заявки на реєстрацію відсутні.</h3></td>";
                    } else { 
                        while ($row = mysqli_fetch_assoc ( $result )) {
                ?>
                <tr>
                    <td style="vertical-align: middle;"><?= $row['client_id'] ?></td>
                    <td style="vertical-align: middle;"><?= $row['client_name'] ?></td>
                    <td style="vertical-align: middle;"><?= $row['client_establishment'] ?></td>
                    <td style="vertical-align: middle;"><?= $row['client_person_name'] ?></td>
                    <td style="vertical-align: middle;"><?= $row['client_phone_number'] ?></td>
                    <td style="vertical-align: middle;"><?= $row['client_email'] ?></td>
                    <td class="text-center">
                        <a style="padding: 5px 10px;" class='btn btn-success btn-xs' href="clients.php?route=new_clients&client_id=<?php echo $row['client_id']; ?>&approve=true&client_email=<?php echo $row['client_email'] ?>">
                            <i class="fas fa-user-check fa-lg"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <a style="padding: 5px 10px;" class='btn btn-danger btn-xs' href="clients.php?route=new_clients&client_id=<?php echo $row['client_id']; ?>&delete=true&token=<?php echo session_id(); ?>">
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
