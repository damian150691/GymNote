<div id="footer"><span>Powered by WebDM</span></div>
    </div>

    
    
    <?php 
    //check if the current page is the makenewplan page
        $isScriptLoaded = false;
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('/', $url);
        $baseUrl = $url[1];
        $url = end($url);
        $scriptMap = [
            'makenewplan' => function($url) {
                return $url == 'makenewplan';
            },
            'profile' => function($url) {
                return $url == 'profile';
            },
            'admin' => function($url, $baseUrl) {
                return $url == 'admin' || strpos($baseUrl, 'admin') !== false;
            },
            // You can easily add more pages here...
        ];
        
        $isScriptLoaded = false;
        
        foreach ($scriptMap as $page => $condition) {
            if ($condition($url, $baseUrl)) {
                $isScriptLoaded = true;
                echo '<script src="../js/' . $page . '.js"></script>';
                break; // Exit the loop once a script is loaded
            }
        }
        
        if ($isScriptLoaded) {
            echo '<script src="../js/jquery-3.2.1.min.js"></script>';
        }

    ?>
    
    
</body>

</html>