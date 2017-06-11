<html>
    <head>
        <title>CAFE ESTELLA</title>
        <script src="jquery.min.js"></script>
        <link rel="stylesheet" href="CSS.css" type="text/css" />
        <script src="bootstrap.min.js"></script>
    </head>
    <?php
		session_start();
		$connect = mysqli_connect("localhost", "root", "", "test");

	?>
    <body>
        <br />
        <div class="container" style="width:100%;">
            <h3 align="center">CAFE ESTELLA</h3>
            <br />
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#products">Product</a>
                </li>
                <!-- 	<li><a data-toggle="tab" href="#cart">Cart <span class="badge"><?php if(isset($_SESSION["shopping_cart"])) { echo count($_SESSION["shopping_cart"]); } else { echo '0';}?></span></a></li> -->
            </ul>
            <div class="tab-content">
                <div class="col-md-9">
                    <div id="products" class="tab-pane fade in active">
                        <?php
				$query = "SELECT * FROM tbl_product ORDER BY id ASC";
				$result = mysqli_query($connect, $query);
				while($row = mysqli_fetch_array($result))
				{
				?>
                            <div class="col-md-2 col-sm-2" style="margin-top:12px; margin-right:-30px;">
                                <div style="border:0px solid #000; background-color:#F1948A; border-radius:5px; padding:5px; height:19%; width:75%;" align="center">
                                    <!--<img src="images/<?php echo $row["image"]; ?>" class="img-responsive" />-->
                                    <h5 class="text-info"><b><?php echo $row["name"]; ?></b></h5>
                                    <!-- <h5 type="hidden" class="text-danger">Php <?php echo $row["price"]; ?></h5>
                                    <h5 type="hidden" class="text-danger">Php <?php echo $row["price2"]; ?></h5> -->	
                                    <input type="radio" name="size" value="12oz">12oz
									<input type="radio" name="size" value="16oz">16oz
                                    <input type="text" name="quantity" id="quantity<?php echo $row["id"]; ?>" class="form-control" value="1" />
                                    <input type="hidden" name="hidden_name" id="name<?php echo $row["id"]; ?>" value="<?php echo $row["name"]; ?>"/>
                                    <input type="hidden" name="hidden_price" id="price<?php echo $row["id"]; ?>" value="<?php echo $row["price"]; ?>"/>
                                    <input type="hidden" name="hidden_price2" id="price2<?php echo $row["id"]; ?>" value="<?php echo $row["price2"]; ?>"/>
                                    <input type="button" name="add_to_cart" id="<?php echo $row["id"]; ?>" style="margin-top:8px; padding:3px; width: 60px" class="btn btn-warning form-control add_to_cart" value="12oz" />
                                    <input type="hidden" name="add_to_cart_16oz" id="<?php echo $row["id"]; ?>" style="margin-top:6px; padding:3px; width: 60px" class="btn btn-warning form-control add_to_cart_16oz" value="16oz" />
                                </div>
                            </div>
                        <?php
				}
				?>
                    </div>
                    <!-- <div id="cart" class="tab-pane fade">
                    <div class="table-responsive" id="order_table">
                    </div>
                    </div> -->
                </div>

                <div class="col-md-3" style="margin-left:-45px; margin-top:12px; position:fixed; display:inline; ">
                <table class="table table-bordered">
                        <tr>
                            <th width="50%">Product Name</th>
                            <th width="10%">Quantity</th>
                            <th width="20%">Price</th>
                            <th width="15%">Total</th>
                            <th width="5%">Action</th>
                        </tr>
                        <?php
							if(!empty($_SESSION["shopping_cart"]))
							{
								$total = 0;
								foreach($_SESSION["shopping_cart"] as $keys => $values)
								{
							?>
                            <tr>
                                <td><?php  echo $values["product_name"]; ?></td>
                                <td><?php  echo $values["product_quantity"]; ?></td>
                                <td align="right"><?php  echo $values["product_price"]; ?></td>
                                <<!-- td align="right"><?php echo $values["product_price2"]; ?></td> -->
                                <td align="right"><?php  echo number_format($values["product_quantity"] * $values["product_price"], 2); ?></td>
                                <td>
                                    <button name="delete" class="btn btn-danger btn-xs delete" id="<?php echo $values["product_id"]; ?>">Remove</button>
                                </td>
                            </tr>
                        <?php
									$total = $total + ($values["product_quantity"] * $values["product_price"]);
								}
							?>
                        <tr>
                            <td colspan="3" style="text-align:right !important;">Total</td>
                            <td align="right"><?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                    <?php
							}
							?>
                    </table>
                </div>


            </div>
        </div>
    </body>
</html>
<script>
$(document).ready(function(data){
	
	$('.add_to_cart').click(function(){
		var product_id = $(this).attr("id");
		var product_name = $('#name'+product_id).val();
		var product_price = $('#price'+product_id).val();
		var product_price2 = $('#price2'+product_id).val();
		var product_quantity = $('#quantity'+product_id).val();
		var product_size = $("input[name=size]:radio:checked").val();
		var action = "add";
		var action = "add";
		if(product_quantity > 0)
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				dataType:"json",
				data:{
					product_id:product_id, 
					product_name:product_name, 
					product_price:product_price, 
					product_price2:product_price2, 
					product_quantity:product_quantity, 
					action:action
				},
				success:function(data)
				{
					$('#order_table').html(data.order_table);
					$('.badge').text(data.cart_item);
					alert('product_size');
				}
			});
		}
		else
		{
			alert("Please Enter Number of Quantity")
		}
	});

	$(document).on('click', '.delete', function(){
		location.reload();
		var product_id = $(this).attr("id");
		var action = "remove";
		if(confirm("Are you sure you want to remove this product?"))
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				dataType:"json",
				data:{product_id:product_id, action:action},
				success:function(data){
					$('#order_table').html(data.order_table);
					$('.badge').text(data.cart_item);
				}
			});
		}
		else
		{
			return false;
		}
	});

});
</script>