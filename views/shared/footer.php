<div id="footer"><span>Powered by WebDM</span></div>
    </div>

    
    <!-- Scripts -->
    <script src="../js/scripts.js"></script>
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
            'friends' => function($url) {
                return $url == 'friends';
            },
            'exercises' => function($url) {
                return $url == 'exercises';
            },
            'addtrainingsession' => function($url, $baseUrl) {
                return $url == 'addtrainingsession' || strpos($baseUrl, 'addtrainingsession') !== false;
            },
        
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
        } else {
        }

    ?>
    
    
</body>

</html>