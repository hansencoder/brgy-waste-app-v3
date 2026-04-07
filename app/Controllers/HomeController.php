<?php
class HomeController extends Controller {
    public function index() {
        $isLoggedIn = isset($_SESSION['user_id']);
        $role = $isLoggedIn ? $_SESSION['user_role'] : null;

        $this->view('home/index', [
            'isLoggedIn' => $isLoggedIn,
            'role' => $role
        ]);
    }
}
