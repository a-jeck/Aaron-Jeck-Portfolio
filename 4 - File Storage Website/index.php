<?php session_start();

/////////////////  CHANGE PAGE  /////////////////
if (isset($_POST['page'])) {
    $page = (string) $_POST['page'];
} else {
    $page = 'login';
}


switch ($page) {
        /////////////////  UPLOAD CASES  /////////////////
    case 'upload':
        include("upload.php");
        break;
    case 'upload_handler':

        if (isset($_POST['uploadsubmit'])) {
            $filename = basename($_FILES['uploadedfile']['name']);

            if (!preg_match('/^[\w_\.\-]+$/', $filename)) {
                $_SESSION['uploadfailure'] = true;
                include("upload.php");
                exit;
            }

            $username = $_SESSION['username'];
            if (!preg_match('/^[\w_\.\-]+$/', $username)) {
                echo "Invalid username";
                exit;
            }

            $full_path_upload = sprintf("/srv/uploads/%s/%s", $username, $filename);

            if (!(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path_upload))) {
                $_SESSION['uploadfail'] = true;
                $_SESSION['uploadfailurecode'] = $_FILES['uploadedfile']['error'];
            }
        }
        include('home.php');
        break;


        /////////////////  DELETE CASES  /////////////////
    case 'delete':
        include("delete.php");
        break;
    case 'delete_handler';
        if (isset($_POST['yes'])) {
            unlink($_SESSION['currentdir']);
        }
        include('home.php');
        break;


        /////////////////  VIEW  /////////////////
    case 'openpage':
        if (isset($_POST['openfile'])) {
            $_SESSION['file_number'] = (string) $_POST['openfile'];
        }
        include('openpage.php');
        break;


        /////////////////  DOWNLOAD  /////////////////
    case 'download':
        if (isset($_POST['downloadbutton'])) {
            header('Content-Disposition: attachment; filename= ' . $_SESSION['currentfilename'] . '; Content-Type: ' . $_SESSION['currentmime']);
            readfile($_SESSION['currentdir']);
        }
        include('home.php');
        break;


        /////////////////  HOME  /////////////////   
    case 'home':
        if (!empty($_SESSION['currentdir'])) {
            unset($_SESSION['currentdir']);
        }
        if (isset($_POST['login'])) {
            $inputtedusername =  (string) $_POST['username'];
            if ($inputtedusername == 'admin') {
                $_SESSION['isadmin'] = true;
            } else {
                $_SESSION['isadmin'] = false;
                $_SESSION['username'] = $inputtedusername;
            }
        }
        if ($_SESSION['isadmin'] && !$_POST['exit']) {
            include("admin.php");
        } else {
            $lines = file("/srv/uploads/users.txt", FILE_IGNORE_NEW_LINES);
            $validname = false;
            foreach ($lines as $name) {
                if ($_SESSION['username'] == $name) {
                    $validname = true;
                }
            }
            if ($validname) {
                include("home.php");
            } else {
                $_SESSION['wrongname'] = true;
                include("login.php");
            }
        }
        break;


        /////////////////  SIGNUP  /////////////////
    case 'signup':
        include("signup.php");
        break;
    case 'signupconfirm':
        if (isset($_POST['signupexit'])) {
            include('login.php');
            break;
        }
        $newuser = (string) $_POST['newusername'];
        if ($newuser == "" || !preg_match('/^[\w_\.\-]+$/', $newuser)) {
            $_SESSION['failedsignup'] = true;
            include('login.php');
            break;
        } else {
            $lines = file("/srv/uploads/users.txt", FILE_IGNORE_NEW_LINES);
            $isnewname = true;
            foreach ($lines as $name) {
                if ($newuser == $name) {
                    $isnewname = false;
                }
            }
        }
        if (!$isnewname) {
            $_SESSION['duplicatename'] = true;
            include('login.php');
            break;
        } else {
            mkdir("/srv/uploads/" . $newuser);
            $userlist = fopen("/srv/uploads/users.txt", "a");
            fwrite($userlist, $newuser . "\n");
            fclose($userlist);
            $_SESSION['username'] = $newuser;
        }
        include('home.php');
        break;


        /////////////////  ADMIN ACCOUNT SWITCHER  /////////////////   
    case 'changeuser':
        $_SESSION['username'] = (string) $_POST['launch_user'];
        include("home.php");
        break;
    case 'admin':
        unset($_SESSION['username']);
        include('admin.php');
        break;


        /////////////////  ADMIN ACCOUNT DELETER  /////////////////  
    case 'userdeleteconfirm':
        $_SESSION['deleteuserID'] = (string) $_POST['deleteuserID'];
        include('userdeleteconfirm.php');
        break;
    case 'userdelete':
        /// Find the user and make a new users.txt without them
        if (isset($_POST['yesdelete'])) {                                           /// If admin has confirmed the deletion
            $deletenamenumber = $_SESSION['deleteuserID'];                          /// Recover number of user to delete
            $deletename;                                                            /// Initialize variable for the username
            $linesdelete = file("/srv/uploads/users.txt", FILE_IGNORE_NEW_LINES);   /// Read the users.txt file
            $deletecounter = 0;                                                     /// Set the counter to 0
            $newusers = fopen("/srv/uploads/temptext.txt", "w");                    /// Create a new, temporary users.txt
            foreach ($linesdelete as $namedelete) {                                 /// Iterate through users.txt
                if ($deletecounter != $deletenamenumber) {                          /// If the current line is not the user to be deleted
                    fwrite($newusers, $namedelete . "\r\n");                        /// Write their name in the temporary users.txtx
                } else {                                                            /// If they are the user to be deleted
                    $deletename = $namedelete;                                      /// Set the username to that name
                }
                $deletecounter = $deletecounter + 1;                                /// Each loop, +1 on counter
            }
            fclose($newusers);                                                      /// Close the temporary users.txt
            unlink("/srv/uploads/users.txt");                                       /// Delete the old users.txt
            rename("/srv/uploads/temptext.txt", "/srv/uploads/users.txt");          /// Rename the temporary users.txt to users.txt

            /// Delete the user 
            $b = scandir("/srv/uploads/" . $deletename);                            /// Load the user's directory
            foreach ($b as $filenamefordelete) {                                    /// Iterate through every file
                unlink("/srv/uploads/" . $deletename . "/" . $filenamefordelete);   /// Delete every file
            }
            rmdir("/srv/uploads/" . $deletename);                                   /// Once directory is empty, delete it
        }
        include('admin.php');
        break;


        /////////////////  VIEW  /////////////////   
    case 'view':
        include("view.php");
        break;


        /////////////////  LOGIN/LOGOUT  /////////////////   
    case 'login':
        include("login.php");
        break;
    case 'logout':
        unset($_SESSION['isadmin']);
        unset($_POST['page']);
        session_destroy();
        include("login.php");
        break;
}
