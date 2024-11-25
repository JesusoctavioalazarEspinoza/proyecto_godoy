<?php
// app/controllers/ErrorController.php

class ErrorController extends Controller {
    public function show($code = 404, $message = '') {
        http_response_code($code);
        
        return $this->render('error/' . $code, [
            'message' => $message,
            'title' => 'Error ' . $code
        ]);
    }
}