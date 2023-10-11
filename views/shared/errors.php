<?php

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<p class="error">' . $error . '</p>';
    }
}

if (!empty($_SESSION['message'])) {
    echo '<p class="message">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}

