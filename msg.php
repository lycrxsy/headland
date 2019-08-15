<?php
//url type parameter processing 1:successful 2:failed
$type = isset($_GET['type']) && in_array(intval($_GET['type']), array(1, 2)) ? intval($_GET['type']) : 1;
$title = $type == 1 ? 'successful' : 'failed';
$msg = isset($_GET['msg']) ? trim($_GET['msg']) : 'successful';
$url = isset($_GET['url']) ? trim($_GET['url']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEADLAND|<?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/done.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="image_center">
            <?php if($type == 1): ?>
                <span class="smile_face">:)</span>
            <?php else: ?>
                <span class="smile_face">:(</span>
            <?php endif; ?>
        </div>
        <div class="code">
            <?php echo $msg ?>
        </div>
        <div class="jump">
            page forwarding after <strong id="time" style="color: #009f95">3</strong> seconds
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2019 POWERED BY HEADLAND</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        var time = 3;
        var url = "<?php echo $url?>" || null;//js read php syntax
        setInterval(function () {
            if (time > 1) {
                time--;
                console.log(time);
                $('#time').html(time);
            }
            else {
                $('#time').html(0);
                if (url) {
                    location.href = url;
                } else {
                    history.go(-1);
                }
            }
        }, 1000);
    })
</script>
</html>
