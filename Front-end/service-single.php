<?php
    session_start();
    require_once('config.php');
    $name = $price = $serviceLine = $description = " ";
    $star = 0;
    $star_1 = 0;
    $star_2 = 0;
    $star_3 = 0;
    $star_4 = 0;
    $star_5 = 0;
    $count_review = 0;
    if(!isset($_SESSION['cart']) || !isset($_SESSION['number']))
    {
        $_SESSION['cart'] = array();
        $_SESSION['number'] = array();
        $_SESSION['price'] = array();
    }

    if(isset($_GET['id']) && $_GET['id'] != '')
    {
        $query_string = "SELECT *,FORMAT(price, 0) as f_price,(SELECT serviceLine FROM serviceline sl WHERE sl.ID = s.serviceLine) as service_line FROM services s WHERE serviceID =".$_GET['id'];
        $query = mysqli_query($link, $query_string);
        if(mysqli_num_rows($query) > 0)
        {
            $row = mysqli_fetch_assoc($query);
            $name = $row['serviceName'];
            $serviceLine = $row['service_line'];
            $image = $row['serviceImage'];
            $price = $row['f_price']."đ";
            $description = $row['serviceDescription'];
        }
        $query_string = "SELECT COUNT(*) as c FROM servicereview WHERE serviceID =".$_GET['id'];
        $query = mysqli_query($link, $query_string);
        if($row = mysqli_fetch_assoc($query))
        {
            $count_review = $row['c'];
        }
        $sql = "SELECT FORMAT(AVG(`stars`), 0) as avg FROM `servicereview` WHERE `serviceID` = ".$_GET['id']." ORDER BY `serviceID`";
        $query = mysqli_query($link, $sql);
        if($row = mysqli_fetch_assoc($query))
        {
            if(isset($row['avg']))
                $star = $row['avg'];
            $sql = "SELECT FORMAT(100*COUNT(*)/IF((SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")=0, 1, (SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")), 0) as rate FROM servicereview WHERE serviceID = ".$_GET['id']." AND stars = 1";
            $query = mysqli_query($link, $sql);
            if($row_t = mysqli_fetch_assoc($query))
            {
                $star_1 = $row_t['rate'];
            }
            $sql = "SELECT FORMAT(100*COUNT(*)/IF((SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")=0, 1, (SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")), 0) as rate FROM servicereview WHERE serviceID = ".$_GET['id']." AND stars = 2";
            $query = mysqli_query($link, $sql);
            if($row_t = mysqli_fetch_assoc($query)){
                $star_2 = $row_t['rate'];
            }
            $sql = "SELECT FORMAT(100*COUNT(*)/IF((SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")=0, 1, (SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")), 0) as rate FROM servicereview WHERE serviceID = ".$_GET['id']." AND stars = 3";
            $query = mysqli_query($link, $sql);
            if($row_t = mysqli_fetch_assoc($query))
            {
                $star_3 = $row_t['rate'];
            }
            $sql = "SELECT FORMAT(100*COUNT(*)/IF((SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")=0, 1, (SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")), 0) as rate FROM servicereview WHERE serviceID = ".$_GET['id']." AND stars = 4";
            $query = mysqli_query($link, $sql);
            if($row_t = mysqli_fetch_assoc($query))
            {
                $star_4 = $row_t['rate'];
            }
            $sql = "SELECT FORMAT(100*COUNT(*)/IF((SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")=0, 1, (SELECT COUNT(*) FROM servicereview WHERE serviceID = ".$_GET['id'].")), 0) as rate FROM servicereview WHERE serviceID = ".$_GET['id']." AND stars = 5";
            $query = mysqli_query($link, $sql);
            if($row_t = mysqli_fetch_assoc($query))
            {
                $star_5 = $row_t['rate'];
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Petty</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">  
    <link rel="stylesheet" href="./asset/css/base.css">
    <link rel="stylesheet" href="./asset/css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="../Front-end/asset/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../Front-end/asset/css/owl.theme.default.min.css">
</head>
<body>
    <!--Header-->
    <?php
        include "header.php";
    ?>
    <!--Menu-->
    <?php
        include "menu.php";
    ?>
    <!--Content-->
    <div class="product-detail-page content container" style="min-height: 300px;">
        <h3 class="title-catalog">Danh mục</h3>
        <div class="view-product">
            <div class="product-image" style="margin-right: 20px;">
                <img src="<?=$image?>">
            </div>
            <div class="product-detail">
                <div class="product-name">
                    <h3><?=$name?></h3>
                </div>
                <div class="product-status">
                    <div class="rating">
                        <span class="level"><?=$star?></span>
                        <span class="label">
                            <?php
                                for ($i=0; $i < 5; $i++) { 
                                    if($i < $star)
                                    {
                                        echo "<i class='fas fa-star'></i>";
                                    }
                                    else
                                    {
                                        echo "<i class='fas fa-star' style='color:black;'></i>";
                                    }
                                }
                            ?>
                        </span>
                    </div>
                    <div class="number-of-rating"><a href="#demo"><?=$count_review?> <span>đánh giá</span></a></div>
                </div>
                <div class="product-price">
                    <h2><?=$price?></h2>
                </div>
                <div class="product-description">
                   <p>
                        <?=$serviceLine?>
                   </p>
                </div>
                <div class="add-cart" style="margin-top: 20px;">
                    <button type="submit" id="addcart" onclick='window.location.href = "bookservices.php?id=<?=$_GET['id']?>";'>
                        <i class="fas fa-calendar-check"></i>
                        <span>Đặt lịch</span>
                    </button>
                </div>
            </div>
       </div>
       <div class="detailed-description">
            <h3 class="title">Mô tả sản phẩm</h3>
            <div class="text">
                <?=$description?>
            </div>
       </div>
       <!--Phần review của khách hàng-->
       <div class="customer-review">
            <h3 class="title">Nhận xét của khách hàng</h3>
            <div class="rating-overview row shadow">
                <div class="rating-average col-3" style="padding: 30px;">
                    <p>Đánh giá trung bình</p>
                    <h1 style="color: #ef5030;"><?=$star?>/5</h1>
                    <span class="label">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </span>
                </div>
                <div class="rating-percentage col-5">
                    <div class="rate-item rate-5">
                        <div class="rating-num" style="margin-right: 5px;">5<i class="fas fa-star"></i></div>
                        <div class="progress" style="width: 300px;">
                            <div class="progress-bar" style="width:<?php if($star_5 > 10) echo $star_5; else echo "15";?>%"><?=$star_5?>%</div>
                        </div>
                    </div>
                    <div class="rate-item rate-4">
                        <div class="rating-num" style="margin-right: 5px;">4<i class="fas fa-star"></i></div>
                        <div class="progress" style="width: 300px;">
                            <div class="progress-bar" style="width:<?php if($star_4 > 10) echo $star_4; else echo "15";?>%"><?=$star_4?>%</div>
                        </div>
                    </div>
                    <div class="rate-item rate-3">
                        <div class="rating-num" style="margin-right: 5px;">3<i class="fas fa-star"></i></div>
                        <div class="progress" style="width: 300px;">
                            <div class="progress-bar" style="width:<?php if($star_3 > 10) echo $star_3; else echo "15";?>%"><?=$star_3?>%</div>
                        </div>
                    </div>
                    <div class="rate-item rate-5">
                        <div class="rating-num" style="margin-right: 5px;">2<i class="fas fa-star"></i></div>
                        <div class="progress" style="width: 300px;">
                            <div class="progress-bar" style="width:<?php if($star_2 > 10) echo $star_2; else echo "15";?>%"><?=$star_2?>%</div>
                        </div>
                    </div>
                    <div class="rate-item rate-5">
                        <div class="rating-num" style="margin-right: 5px;">1<i class="fas fa-star"></i></div>
                        <div class="progress" style="width: 300px;">
                            <div class="progress-bar" style="width:<?php if($star_1 > 10) echo $star_1; else echo "15";?>%"><?=$star_1?>%</div>
                        </div>
                    </div>

                </div>
                <div class="sharing-comment col">
                    <p>Chia sẻ nhận xét của bạn</p>
                    <!--Button để mở modal nhận xét-->
                    <button data-toggle="modal" data-target="#comment-modal">Viết nhận xét của bạn</button>
                </div>
            </div>

            <div class="product-review-box" style="padding: 20px;">
                <div class="review-filter">
                    <form>
                        <label for="filter" class="mr-3 ml-5">Chọn xem nhận xét</label>
                        <select id="filter" style="width: 200px; height: 30px; border-radius: 5px;">
                            <option value="all">Tất cả</option>
                            <option value="5sao">5 sao</option>
                            <option value="4sao">4 sao</option>
                            <option value="3sao">3 sao</option>
                            <option value="2sao">2 sao</option>
                            <option value="1sao">1 sao</option>
                        </select>
                    </form>
                </div>
                <?php
                    $sql = 'SELECT * FROM servicereview WHERE serviceID = '.$_GET['id'];
                    $query = mysqli_query($link, $sql);
                    while($row = mysqli_fetch_assoc($query))
                    {
                        $sql = 'SELECT * FROM users WHERE ID = '.$row['customerID'];
                        $query_t = mysqli_query($link, $sql);
                        if($row_t = mysqli_fetch_assoc($query_t))
                        {
                            echo "<div class='item-review row' style='padding: 20px;'>
                            <div class='col-2' style='text-align: center;'>
                                <img src='https://cdn4.iconfinder.com/data/icons/interface-14/256/interface_user-512.png' class='rounded-circle' style='width: 80px;'>
                                    <div class='customer-name'>".$row_t['username']."</div>
                                    <div class='posted-time'>2 tháng trước</div>
                            </div>
                            <div class='col review-description'>
                                <div>
                                    <span class='label'>";
                            for($i=0; $i<5; ++$i)
                            {
                                if($i < $row['stars'])
                                {
                                    echo "<i class='fas fa-star'></i>";
                                }
                                else
                                {
                                    echo "<i class='fas fa-star' style='color:black'></i>";
                                }
                            }
                            echo " 
                                    </span>
                                    <span class='ml-2'>".$row['subtitle']."</span>
                                </div>
                                <div style='color: #ccc;'><i>Đã mua sản phẩm</i></div>
                                <div>".
                                    $row['comment']   
                                ."</div>
                            </div>
                            </div>";
                        } 
                    }
                ?>
            </div>
       </div>
       <!--Đây là modal cho comment-->
       <div class="modal fade" id="comment-modal">
        <div class="modal-dialog">
          <div class="modal-content">
      
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Viết nhận xét</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
      
            <!-- Modal body -->
            <div class="modal-body">
              <form>
                <label for="star-rating" style="margin-right: 10px;">1.Đánh giá của bạn về sản phẩm này: </label>
                <span id="star-row">
                    <i id="s1" class="vote-star fas fa-star"></i>
                    <i id="s2" class="vote-star fas fa-star"></i>
                    <i id="s3" class="vote-star fas fa-star"></i>
                    <i id="s4" class="vote-star fas fa-star"></i>
                    <i id="s5" class="vote-star fas fa-star"></i>
                </span>
                <br>
                <label for="comment-heading">2.Tiêu đề của nhận xét: </label>
                <input class="form-control" type="text" id='subtitle' placeholder="Tiêu đề..."></input><br>
                <label for="comment">3.Viết nhận xét của bạn bên dưới:</label>
                <textarea class="form-control" rows="5" id="comment" placeholder="Viết nhận xét của bạn ở đây..."></textarea>
              </form>
            </div>
      
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn" id="submit-comment" data-dismiss="modal" style="background-color: #f19f1f; color: #fff;">Gửi nhận xét</button>
            </div>
      
          </div>
        </div>
      </div>
       <!--Hết phần review của khách hàng-->
       
    </div>

    <!--Footer-->
    <?php
        include "footer.php";
    ?>
    <script>
        $(document).ready(function(){
            var starNumber = 0;
            $(".vote-star").click(function(){
                var n = parseInt(this.getAttribute("id").substring(1, 2));
                starNumber = n;
                for (var i = 1; i <= 5; ++i) {
                    if(i <= n)
                    {
                        $('#s'+i).css("color", "#FFC400");
                    }
                    else
                    {
                        $('#s'+i).css("color", "black");
                    }
                }
            });
            $('#submit-comment').click(function(){
                if(<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) echo"true"; else echo "false";?>)
                {
                    var subtitle = document.getElementById('subtitle').value;
                    var comment = document.getElementById('comment').value;
                    var userID = <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) echo $_SESSION['id']; else echo 0;?>;
                    var serviceID = <?=$_GET['id']?>;
                    if(starNumber != 0 && subtitle != '' && comment != '')
                    {
                        $.ajax({
                            url:'addServiceComment.php',
                            method:'POST',
                            data:{userID:userID, serviceID:serviceID, subtitle:subtitle, comment:comment, starNumber:starNumber},
                            success:function(data)
                            {
                                console.log(data);
                            }
                        })  
                    }
                }
            });
            $('#addcart').click(function(){
                $('.toast').toast('show');
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }) 
        });
    </script>
</body>
</html>