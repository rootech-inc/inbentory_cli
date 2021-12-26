<?php
    if (session_status() === PHP_SESSION_NONE) {
        // check for token
        session_start();
    }



