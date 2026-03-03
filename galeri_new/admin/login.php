
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    include "koneksi.php";
    if(!empty($_SESSION['id_user'])){
        echo "<script>location='.'</script>";
    }else{
        if(isset($_POST['login'])){
            $user = $_POST['txtuser'];
            $pass =$_POST['txtpass'];
            
            $sql = "SELECT * FROM user WHERE username = '$user' AND password ='$pass'";
            $q = mysqli_query($k, $sql);
                if(mysqli_num_rows($q) > 0){
                $r = mysqli_fetch_assoc($q);
                $_SESSION['id_user'] = $r['id_user'];
                $_SESSION['nama_user'] = $r['nama_user'];
                echo "<script>location='.'</script>";
            }else{
                echo "<script>alert('Data yang dimasukkan salah');location='login.php'</script>";
            }
        }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    Web Wakil Mentri Hak Asasi Manusia
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="bg-gray-200">

  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-color: #1a499c;">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Login Admin</h4>
                  <div class="row mt-3">
                    <div class="col-2 text-center ms-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-facebook text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center px-1">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-github text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center me-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-google text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form role="form" class="text-start" method="post" action="">
                  <div class="mb-3">
                      <label for="txtuser" class="form-label text-dark">Username</label>
                      <input name="txtuser" id="txtuser" type="text" class="form-control" placeholder="Masukkan Username Admin">
                    </div>

                    <div class="mb-3">
                      <label for="txtpass" class="form-label text-dark">Password</label>
                      <input name="txtpass" id="txtpass" type="password" class="form-control" placeholder="Masukkan Password Admin">
                    </div>
                  <div class="text-center">
                    <input name="login" type="submit" value="Login" class="btn bg-gradient-dark w-100 my-4 mb-2" />
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
            <div class="container">
                <div class="row align-items-center justify-content-lg-between">
                <div class="col-12 col-md-12 my-auto">
                    <div class="copyright text-center text-sm text-white">
                    © <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    Web Admin <i class="fa fa-heart" aria-hidden="true"></i> to
                    <a href="https://www.creative-tim.com" class="font-weight-bold text-white" target="_blank">Mugiyanto.id</a>
                    </div>
                </div>
                </div>
            </div>
        </footer>


    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
<?php } ?>