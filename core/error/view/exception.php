<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>异常提示</title>
    <style type="text/css">
        div.main {
            font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
            font-family: "Microsoft Yahei", "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding: 10px;
            margin-left: 30px;
            color: #333;
        }

        div.pic {
            font-size: 105px;
            padding-bottom: 10px;
            font-family: "微软雅黑";
            font-size: 128px;
        }

        div.msg {
            font-size: 35px;
            margin-bottom: 30px;
            font-weight: bold;
            color: #000;
        }

        div.info {
            font-size: 30px;
            margin-bottom: 10px;
        }

        div.info div.title, div.trace div.title {
            font-size: 18px;
            font-weight: bold;;
        }

        div.info div.path {
            font-size: 16px;
            line-height: 1.5em;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="pic">
        :(
    </div>
    <div class="msg">
        <?php echo $e->getMessage(); ?>
    </div>
    <div class="info">
        <div class="title">
            错误位置:
        </div>
        <div class="path">
            <?php echo 'File:' . $e->getFile() . '  Line:' . $e->getLine(); ?>
        </div>
    </div>
    <div class="info">
        <div class="title">
            Trace
        </div>
        <div class="path">
            <?php echo nl2br($e->__toString()); ?>
        </div>
    </div>
</div>
</body>
</html>