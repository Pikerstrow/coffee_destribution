
<div class="panel-group" id="accordion">
   <div class="panel panel-default">
      <?php
      while (mysqli_stmt_fetch($stmt)) {
         ?>
         <div class="panel-heading">
            <h4 class="panel-title panel-title-order">
               <a style="text-decoration: none; display:inline-block; width:100%;" data-toggle="collapse"
                  data-parent="#accordion"
                  href="#collapse<?php echo $counter; ?>"><?php echo $cat_title; ?></a>
            </h4>
         </div>
         <div id="collapse<?php echo $counter; ?>"
              class="panel-collapse collapse <?php echo($counter == 1 ? 'in' : ''); ?>">
            <div class="panel-body">
               <?php
               $query = "SELECT product_id, product_image, product_title, product_description, product_price, product_category_id FROM my_coffee_products WHERE product_category_id = {$cat_id} ORDER BY product_id DESC";
               $result = mysqli_query($link, $query);

               if (mysqli_num_rows($result)) {
                  ?>
                  <div class="table-responsive"
                       id="table_with_goods<?php echo $counter; ?>">
                     <table class="table table-bordered table-admin table-products products_list_table">
                        <thead>
                        <tr style="font-size:14.5px;">
                           <th style="vertical-align: middle">ID</th>
                           <th style="vertical-align: middle; width:120px;">Фото</th>
                           <th style="vertical-align: middle; width:150px; ">Назва</th>
                           <th style="vertical-align: middle">Опис</th>
                           <th style="vertical-align: middle; width: 90px;">Ціна,
                              <small>грн</small>
                           </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                           <tr style="font-size:13.5px;">
                              <td style="vertical-align: middle"><?php echo $id = $row['product_id']; ?></td>
                              <td
                                 style="vertical-align: middle"><?php echo "<img class='prod-img' width='40' src='../images/products/" . image_is_set($row['product_image']) . "'>" ?></td>
                              <td style="vertical-align: middle"><?php echo $row['product_title']; ?></td>
                              <td style="vertical-align: middle"><?php echo $row['product_description']; ?></td>
                              <td
                                 style="vertical-align: middle"><?php echo number_format($row['product_price'], 2, '.', ' '); ?></td>

                           </tr>
                        <?php endwhile ?>
                        </tbody>
                     </table>
                  </div>

                  <?php
               } else {
                  echo "<h3 style='margin:0;' class='text-center'>В даній категорії товари відсутні!</h3>";
               }
               ?>
            </div>
         </div>
         <!-- JS PAGINATION FOR TABLES WITH PRODUCTS-->
         <script>
             $(document).ready(function () {

                 $('#table_with_goods<?php echo $counter; ?>').after("<div id='table_nav<?php echo $counter; ?>'><button class='a-prev btn btn-sm'><i class=\"fas fa-backward\"></i> </button> <button class='a-next btn btn-sm'><i class=\"fas fa-forward\"></i> </button> <span class='pages-descr'>Сторінка <span class='page-num'> </span> із <span style='font-weight:bold' class='total_pages'> </span></span></div>");

                 $('div#table_nav<?php echo $counter; ?> button.a-prev').prop('disabled', true);
                 var rows_to_show = 5;
                 var total_rows_quantity = $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').length;
                 var pages_quantity = Math.ceil(total_rows_quantity / rows_to_show);
                 $('div#table_nav<?php echo $counter; ?> span.total_pages').text(pages_quantity);

                 if(pages_quantity == 1){
                     $('div#table_nav<?php echo $counter; ?> button.a-next').prop('disabled', true);
                 }

                 $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').hide();
                 $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').slice(0, rows_to_show).show();

                 var current_page = 0;

                 $('#table_nav<?php echo $counter; ?> .a-next').bind('click', function () {
                     $('div#table_nav<?php echo $counter; ?> button.a-prev').prop('disabled', false);
                     if(current_page < pages_quantity-1){
                         current_page++;
                     }
                     if (current_page == pages_quantity-1) {
                         $(this).prop('disabled', true);
                     }

                     var start = current_page * rows_to_show;
                     var end = start + rows_to_show;

                     $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);
                     $('span.page-num').text(current_page+1);
                 });

                 $('#table_nav<?php echo $counter; ?> .a-prev').bind('click', function () {
                     $('div#table_nav<?php echo $counter; ?> button.a-next').prop('disabled', false);

                     if(current_page > 0){
                         current_page--;
                     }
                     if (current_page == 0) {
                         $(this).prop('disabled', true);
                     }

                     var start = current_page * rows_to_show;
                     var end = start + rows_to_show;

                     $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);
                     $('span.page-num').text(current_page+1);
                 });

                 $('span.page-num').text(current_page+1);


             });
         </script>
         <?php
         $counter++;
//      }
      }// End of the first while
      ?>
   </div>
</div>