<?php

class site
{
    private $page;
    private $about;

    function gen_opening($page = "")
    {

        $this->page = $page;
        ?>
        <!DOCTYPE HTML>
        <HTML>
        <HEAD>
            <TITLE>
                Labs Captcha
            </TITLE>
            <meta charset="UTF-8">
            <LINK REL="stylesheet" href="res/css/bootstrap.css"/>
            <style type="text/css">
                body {
                    padding-top: 60px;
                    /*        padding-top: 20px;
                    */
                    padding-bottom: 40px;
                }

                /* Custom container */
                .container-narrow {
                    margin: 0 auto;
                    max-width: 700px;
                }

                .container-narrow > hr {
                    margin: 30px 0;
                }

            </style>
        </HEAD>
        <BODY>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <span class="navbar-brand">Captcha</span>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-10">

        <div class="row marketing">
        <div class="col-md-12">

        <?php
        if ($GLOBALS["message"]) {
            echo "<div class=\"alert alert-warning\">" . $GLOBALS["message-text"] . "</div>";
        }
        ?>
        <noscript>
            <div class="alert alert-danger">
                Portions of this tool require Javascript. Please enable Javasript in your browser to continue.
            </div>
        </noscript>

    <?php
    }


    function gen_closing()
    {
        ?>
        <hr>

      <div class="footer">
        <p style="text-align:right"><small>Captcha version <?php echo $GLOBALS["version"] ?> (<a href="about.php">About this tool</a>)<br /></p>
      </div>
    </div>

    </div> <!-- /col-md-10 -->
    <div class="col-md-1">&nbsp;</div>

    </div> <!-- /container -->
</BODY>
</HTML>
<?php
    }
}