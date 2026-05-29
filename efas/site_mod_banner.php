<section class="slider">
    <div class="flexslider">
        <ul class="slides">
            <!--
            <li>
                <img src="images/banner1.jpg" draggable="false">
                
            </li>

                <li>
                    <img src="images/banner_Principal.jpg" draggable="false">
            </li>
            -->

            <li>
                <img src="images/bannerefas2024.jpg" draggable="false">
            </li>
            
        </ul>
    </div>
</section>


    <!--FlexSlider-->
    <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
    <script defer src="js/jquery.flexslider.js"></script>
    <script type="text/javascript">
    $(function(){
    SyntaxHighlighter.all();
    });
    $(window).load(function(){
    $('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
        $('body').removeClass('loading');
        }
    });
    });
</script>