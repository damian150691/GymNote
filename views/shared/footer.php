<div id="footer"><span>Powered by WebDM</span></div>
    </div>

    
    
    <?php 
    //check if the current page is the makenewplan page
        $isScriptLoaded = false;
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('/', $url);
        $url = end($url);
        if ($url == 'makenewplan') {
            $isScriptLoaded = true;
            echo '<script src="../js/mnp.js"></script>';
        } 
        if ($url == 'admin') {
            $isScriptLoaded = true;
            echo '<script src="../js/admin.js"></script>';
        }

        if ($isScriptLoaded) {
            echo '<script src="../js/jquery-3.2.1.min.js"></script>';
        }

    ?>
    
    
</body>

</html>