<h2 class="h2-panel">
    <span>Товари</span>
</h2>
<hr>
<div class="col-xs-12 col-sm-3 ">
    <h3>Всі товари </h3>
    
    <a class='prod-cat-admin-butt <?php echo (!isset($_GET['category']) ? 'active_li' : ''); ?>' style="margin-bottom: 10px;" href='products.php'><span class="i-wrap"><i class="fas fa-boxes"></i></span>&nbsp; Всі товари</a>
    <?php
    $token = session_id();

    $query    = "SELECT category_id, category_title FROM my_coffee_prod_categories";
    $stmt     = mysqli_prepare($link, $query);
    $result   = mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $cat_id, $cat_title);
                mysqli_stmt_store_result($stmt);
    $quantity = mysqli_stmt_num_rows($stmt);
    
    if (!$quantity) {        
        echo "Категорії відсутні";
    } else {
        echo "<h3>По категоріях </h3>";
        while (mysqli_stmt_fetch($stmt)) {
            ?>
                <a class='prod-cat-admin-butt  <?php echo (isset($_GET['category']) and $_GET['category'] == $cat_id) ? 'active_li' : '' ?>' href='products.php?category=<?=$cat_id?>'>
                    &nbsp; <?=$cat_title?>
                </a>
            <?php
        }
    }
    ?>

</div>    

<div class="col-xs-12 col-sm-9 ">
    <h3>Перелік</h3>
    <div class="table-responsive" id="table_with_goods">
        <table class="table table-bordered table-admin table-products">
            <thead>
                <tr>
                    <th style="vertical-align: middle; font-size:14px;">ID</th>
                    <th style="vertical-align: middle; width:120px; font-size:14px;">Фото товару</th>
                    <th style="vertical-align: middle; width:150px; font-size:14px;">Назва</th>
                    <th style="vertical-align: middle; font-size:14px;">Опис </th>
                    <th style="vertical-align: middle; width: 80px; font-size:14px;">Ціна, <small>грн</small> </th>
                    <th style="text-align: center; vertical-align: middle; font-size:14px;" colspan='2'>Правки</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (isset($_GET['category'])) {
                        $category = $_GET['category'];
                        
                        $query    = "SELECT product_id, product_image, product_title, product_description, product_price ";
                        $query   .= "FROM my_coffee_products WHERE product_category_id = ? ORDER BY product_id DESC";
                        $stmt     = mysqli_prepare($link, $query);
                                  
                                    mysqli_stmt_bind_param($stmt, 'i', $category);
                                    mysqli_stmt_execute($stmt);
                                    mysqli_stmt_bind_result($stmt, $product_id, $product_photo, $product_name, $product_description, $product_price);
                                    mysqli_stmt_store_result($stmt);
                        $quantity = mysqli_stmt_num_rows($stmt);
                        
                        if (!$quantity) {
                            echo "<tr>";
                                echo "<td align='center' colspan='7'><b>Товари відсутні</b></td>";
                            echo "</tr>";
                        } else {
                            while (mysqli_stmt_fetch($stmt)) {
                                    echo "<tr>";
                                    echo "<td style='vertical-align: middle'>{$product_id}</td>";
                                    echo "<td style='vertical-align: middle; text-align: center;'><img class='prod-img' width='45' src='../images/products/" . image_is_set($product_photo) . "'></td>";
                                    echo "<td style='vertical-align: middle'>{$product_name}</td>";
                                    echo "<td style='vertical-align: middle'>{$product_description}</td>";
                                    echo "<td style='vertical-align: middle'>" . number_format($product_price, 2,'.',' ') . "</td>";
                                    echo "<td style='text-align: center; vertical-align: middle'><a title=\"Редагувати\" class='btn btn-primary btn-xs' style=\"padding: 5px 10px;\" href='products.php?route=edit_product&prod_id_edit={$product_id}&token={$token}'><i class=\"far fa-edit fa-lg\"></i></a></td>";
                                    ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                                    <?php 
                                    echo "<td style='text-align: center; vertical-align: middle'><button title=\"Видалити\" class='btn btn-danger btn-xs' style=\"padding: 5px 10px;\" type='submit' name='delete'><i class=\"far fa-trash-alt fa-lg\"></i></button></td>";
                                    ?>
                                    </form>
                                    <?php
                                    echo "</tr>";
                            }
                        }
                    } else {
                        $query    = "SELECT product_id, product_image, product_title, product_description, product_price ";
                        $query   .= "FROM my_coffee_products ORDER BY product_id DESC";
                        $stmt     = mysqli_prepare($link, $query);
                                    mysqli_stmt_execute($stmt);
                                    mysqli_stmt_bind_result($stmt, $product_id, $product_photo, $product_name, $product_description, $product_price);
                                    mysqli_stmt_store_result($stmt);
                        $quantity = mysqli_stmt_num_rows($stmt);

                        if (!$quantity) {
                            echo "<tr>";
                                echo "<td align='center' colspan='7'><b>Товари відсутні</b></td>";
                            echo "</tr>";
                        } else {
                            while (mysqli_stmt_fetch($stmt)) {
                                    echo "<tr>";
                                    echo "<td style='vertical-align: middle'>{$product_id}</td>";
                                    echo "<td style='vertical-align: middle; text-align: center;'><img class='prod-img' width='45' src='../images/products/" . image_is_set($product_photo) . "'></td>";
                                    echo "<td style='vertical-align: middle'>{$product_name}</td>";
                                    echo "<td style='vertical-align: middle'>{$product_description}</td>";
                                    echo "<td style='vertical-align: middle'>" . number_format($product_price, 2,'.',' ') . "</td>";
                                    echo "<td style='text-align: center; vertical-align: middle'><a title=\"Редагувати\" style=\"padding: 5px 10px;\" class='btn btn-primary btn-xs' href='products.php?route=edit_product&prod_id_edit={$product_id}&token={$token}'><i class=\"far fa-edit fa-lg\"></i></a></td>";
                                    ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                                    <?php 
                                    echo "<td style='text-align: center; vertical-align: middle'><button title=\"Видалити\" class='btn btn-danger btn-xs' style=\"padding: 5px 10px;\" type='submit' name='delete'><i class=\"far fa-trash-alt fa-lg\"></i></button></td>";
                                    ?>
                                    </form>
                                    <?php
                                    echo "</tr>";
                            }
                        }
                    }
                ?>
                <?php delete_products() ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Pagination for table with products -->
<script>

    $(document).ready(function () {
        $('#table_with_goods').after("<div id='table_nav'><button class='a-prev btn'><i class=\"fas fa-backward\"></i> </button> <button class='a-next btn'><i class=\"fas fa-forward\"></i> </button> <span class='pages-descr'>Сторінка <span class='page-num'> </span> із <span class='total_pages'> </span></span></div>");

        $('button.a-prev').prop('disabled', true);

        var rows_to_show = 5;
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