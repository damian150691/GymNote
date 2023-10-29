<div id="footer"><span>Powered by WebDM</span></div>
    </div>

    
    
    <?php 
    //check if the current page is the makenewplan page
        $isScriptLoaded = false;
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('/', $url);
        $baseUrl = $url[1];
        $url = end($url);
        if ($url == 'makenewplan') {
            $isScriptLoaded = true;
            echo '<script src="../js/mnp.js"></script>';
        } 
        //check if url is admin or admin/* (admin with any other string after it)
        if ($url == 'admin' || strpos($baseUrl, 'admin') !== false) {
            $isScriptLoaded = true;
            echo '<script src="../js/admin.js"></script>';
        }

        if ($isScriptLoaded) {
            echo '<script src="../js/jquery-3.2.1.min.js"></script>';
        }

    ?>
    
    
</body>

</html>