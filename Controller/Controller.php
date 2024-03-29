<?php
namespace Controller;

session_start();
use Database\Database;
use http\Header;
use Model\User;

include_once('Database/database.php');
include_once('Model/User.php');

class Controller
{
    public function doSignUp($rq)
    {
        $db = new Database();
        $bo = $db->checkUserName($rq['user_name']);
        if ($bo) {
            $bo = $db->storeUser($rq['full_name'], $rq['birth'], $rq['user_name'], $rq['password']);
            $db->close();
            if ($bo) {
                echo '<script>alert("Đăng ký thành công!");window.location.href="./index.php";</script>';
            } else {
                $errors = array('Xảy ra lỗi!');
                $_SESSION['errors'] = $errors;

                Header('Location: ./index.php?controller=ViewController&function=returnSignUp');
            }
        } else {
            $errors = array('Tên đăng nhập đã tồn tại!');
            $_SESSION['errors'] = $errors;

            Header('Location: ./index.php?controller=ViewController&function=returnSignUp');
        }
    }

    public function doLogin($rq)
    {
        $db = new Database();
        $result = $db->checkLogin($rq['user_name'], $rq['password']);
        $db->close();
        if ($result) {
            session_start();
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['user_name'] = $result['user_name'];
            $_SESSION['user_role'] = $result['role'];
            if ($result['role'] === 2) {
                Header('Location: index.php?controller=ViewController&function=returnAdminHome');
            } else {
                Header('Location: index.php?controller=ViewController&function=returnClientHome');
            }
        } else {
            $errors = array();
            array_push($errors, "Tài khoản hoặc mật khẩu sai");
            $_SESSION['errors'] = $errors;

            Header('Location: ./index.php');
        }
    }

    public function doLogout()
    {
        session_unset();
        Header('Location: index.php');
    }

    public function doUpdateProfile($rq)
    {
        $db = new Database();
        $result = $db->doUpdateUser($rq['id'], $rq['full_name'], $rq['address'], $rq['birth']);
        $db->close();
        if ($result) {
            echo '<script>alert("Cập nhật thành công");window.location.href="./index.php";</script>';
        } else {
            echo '<script>alert("Cập nhật lỗi!");window.location.href="./index.php";</script>';
        }
    }

    public function doUpdateUser($rq)
    {
        $db = new Database();
        $result = $db->doUpdateUser($rq['id'], $rq['full_name'], $rq['address'], $rq['birth']);
        $db->close();
        if ($result) {
            echo '<script>alert("Cập nhật thành công");window.location.href="./index.php?controller=ViewController&function=returnUserManagement";</script>';
        } else {
            $errors = array();
            array_push($errors, 'Cập nhật lỗi');
            $_SESSION['errors'] = $errors;

            Header('Location: index.php?controller=ViewController&function=returnUpdateUser&id=' . $rq['id']);
        }
    }

    public function doDeleteUser($id)
    {
        $db = new Database();
        $result = $db->deleteUser($id);
        $db->close();
        if ($result) {
            echo '<script>alert("Xóa thành công");window.location.href="./index.php?controller=ViewController&function=returnUserManagement";</script>';
        } else {
            echo '<script>alert("Có lỗi xảy ra !");window.location.href="./index.php?controller=ViewController&function=returnUserManagement";</script>';
        }
    }

    public function doChangePassword($rq)
    {
        $errors = array();
        $db = new Database();
        if (isset($_SESSION['user_name'])) {
            $user = $db->checkLogin($_SESSION['user_name'], $rq['current_password']);
            if ($user) {
                $result = $db->changePassword($user['id'], $rq['new_password']);
                if ($result) {
                    echo '<script>alert("Đổi mật khẩu thành công!");window.location.href="./index.php";</script>';
                } else {
                    array_push($errors, "Lỗi!");
                    $db->close();
                    $_SESSION['errors'] = $errors;

                    Header('Location: index.php?controller=ViewController&function=returnAdminChangePassword');
                }
            } else {
                array_push($errors, 'Mật khẩu hiện tại sai!');
                $db->close();
                $_SESSION['errors'] = $errors;

                Header('Location: index.php?controller=ViewController&function=returnAdminChangePassword');
            }
        } else {
            array_push($errors, 'Lỗi!');
            $db->close();
            $_SESSION['errors'] = $errors;

            Header('Location: index.php?controller=ViewController&function=returnAdminChangePassword');
        }
    }
}
