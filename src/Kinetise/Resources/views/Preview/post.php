<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h1><?php echo $this->view->post->post_title ?></h1>
        <p><small><?php echo $this->view->post->post_date_gmt ?></small></p>
        <hr>
        <p><?php echo $this->view->post->post_content ?></p>
    </body>
</html>