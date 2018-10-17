<?php
session_start();
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang=en>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link href="css/footer.css" rel="stylesheet">
<head>
  <title>API Interface for Rigor's Full WebSite Analysis</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="d-flex bd-highlight">
      <div class="p-2 bd-highlight">
        <img src="/images/akamai_logo.png" width="88" height="37" class="d-inline-block align-top" alt="">
      </div>
      <div class="p-2 bd-highlight">
        <a class="navbar-brand" style="font-size: 23px; color: #7c7c7c" href="#">API Interface for Rigor's Full WebSite Analysis</a>
      </div>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="navbar-menu" style="margin-left: 70%">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" style="font-size: 23px; color: #7c7c7c" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="font-size: 23px; color: #7c7c7c" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="font-size: 23px; color: #7c7c7c" href="#">Help</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <!--row for tool header -->
    <div class="row">
      <div class="col-md-6">
        <label for="basic-url" style="font-size: 16px; margin: 25px 0px 25px 0px">API Interface for Rigor's Full WebSite Analysis.</label>
      </div>
    </div>
    <!--row for tool form -->
    <div class="row" style="margin: 0px 5px 0px 0px">
      <!-- form column-->
      <div class="col-md-6">
        <div class="card" style="font-size: 11px">
          <div class="card-header">
            <p class="card-text">Step 1: Enter email to send report and upload bulk file with URLs. Bulk file should be a plain text file and each URL in a new line.</p>
            <p class="card-text">Example of file's format:</p>
            <div>
              <p style="margin: 0px 0px 0px 0px">www.yoursite1.com</p>
              <p style="margin: 0px 0px 0px 0px">http://www.yoursite2.com</p>
              <p style="margin: 0px 0px 0px 0px">https://www.yoursite3.com</p>
            </div>
          </div>
          <div class="card-body">
            <form class="needs-validation" action="prod_rigor_full_api.php" method="POST" enctype="multipart/form-data" novalidate>
              <!-- row for email details -->
              <div class="form-row">
                <div class="form-group">
                  <label for="validationEmail">Send Status Report For Full WebSite Analysis:</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text" style="height: calc(1.5rem + 2px); font-size: 100%" id="inputGroupPrepend">@</span>
                    </div>
                    <input type="text" name="email_address" class="form-control" style="height: calc(1.5rem + 2px); font-size: 100%" id="validationEmail" placeholder="Email" aria-describedby="inputGroupPrepend" required>
                    <div class="invalid-feedback">
                      Please enter an email.
                    </div>
                  </div>
                </div>
              </div>
              <!-- row for bulk file details -->
              <div class="form-row">
                <div class="form-group">
                  <label for="validationBulkFile">Bulk file with URLs to analyze:</label>
                  <div class="input-group">
                    <!-- <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                    </div> -->
                    <!-- <label class="custom-file-label" for="validationBulkFile">Choose a file</label> -->
                    <input type="file" name=bulk_full_web class="form-control-file" id="validationBulkFile" aria-describedby="inputGroupFileAddon01" required>
                    <div style="margin-top: 5px; font-size: 16px">
                      <span class="badge badge-warning">Use a plain text file.</span>
                    </div>
                    <div class="invalid-feedback">
                      Please choose a file.
                    </div>
                  </div>
                </div>
              </div>
              <!-- row for submit button -->
              <div class="form-row">
                <div class="">
                  <button class="btn btn-primary btn-sm" name="process_in_file" type="submit">Process File</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- results after processing bulk file column-->
      <div class="col-md-6">
        <div class="card" style="font-size: 11px">
          <div class="card-header">
            <p class="card-text">Step 2: Select a testing region for each URL.</p>
          </div>
          <div class="card-body">
            <form method="POST" action="prod_rigor_full_api.php" enctype="multipart/form-data" novalidate>
              <?php
                if (isset($_POST['process_in_file'])){
                  // echo '<p class="card-text">Default location: na-us-virginia</p>';
                  // process_urls();
                  $_SESSION['mail_report'] = $_POST['email_address'];
                  $_SESSION['array_selects'] = array();
                  $_SESSION['array_selects'] = process_in_file_to_table();
                }
                if (isset($_POST['submit_urls']))
                {
                  echo '<p class="card-text">Default location: na-us-virginia</p>';
                  echo '<table id="fullwebsite_results_processed" class="table table-striped table-sm" style="font-size: 12px">';
                    echo '<thead>';
                      echo '<th scope="row">URL</th>';
                      echo '<th>Test Region</th>';
                    echo '</thead>';
                    echo '<tbody>';
                      for($i = 0 ; $i < count($_SESSION['array_selects']) ; $i++) {
                        $formated_select = $_SESSION['array_selects'][$i];
                        $formated_select = rtrim($formated_select);
                        $replaced_formated_select = str_replace(".","_", $formated_select);
                        $selected_val = $_POST[$replaced_formated_select];
                        $selected_val = rtrim($selected_val);
                        $replaced_formated_select_dot = str_replace("_",".", $formated_select);
                        echo '<tr>';
                        if($selected_val === "Website not responding"){
                          echo '<th scope="row">'.$replaced_formated_select_dot.'<span class="badge badge-danger" style="margin-left: 5px; font-size: 12px">Error</span></th>';
                          echo '<td><input class="form-control form-control-sm" style="height: calc(1.5rem + 2px); font-size: 75%" type="text" value="Website not responding" readonly="readonly" name="'.$replaced_formated_url.'"></td>';
                        }
                        else{
                          echo '<th scope="row">'.$replaced_formated_select_dot.'</th>';
                          echo '<td><input class="form-control form-control-sm" style="height: calc(1.5rem + 2px); font-size: 75%" type="text" value="'.$selected_val.'" readonly="readonly" name="'.$replaced_formated_url.'"></td>';
                        }
                        echo '<tr>';
                      }
                      echo '</tr>';
                    echo '</tbody>';
                  echo '</table>';
                  echo '<div class="form-row">';
                    echo '<div class="">';
                      echo '<button class="btn btn-primary btn-sm" name="submit_urls" type="submit" disabled>Submit Urls</button>';
                    echo '</div>';
                  echo '</div>';
                }
                ?>
            </form>
            </div>
          </div>
        </div>
    </div>
    <!-- results send API call for ULS-->
    <div class="row" style="margin: 30px 5px 0px 0px">
      <div class="col-md-12">
        <div class="card" style="font-size: 11px">
          <div class="card-header">
            <p class="card-text">Step 3: Check the results.</p>
          </div>
          <div class="card-body">
            <?php
            if (isset($_POST['submit_urls']))
            {
            echo '<div class="table-responsive-sm" style="width: 100%">';
              echo '<table id="fullwebsite_results_processed" class="table-striped table-sm" style="font-size: 11px; width: 100%">';
                echo '<thead style="text-align: center">';
                  echo '<th scope="row">Status</th>';
                  echo '<th>URL</th>';
                  echo '<th>Rigor Test Name</th>';
                  echo '<th>Test Location</th>';
                  echo '<th>Snapshot Link/Error Message</th>';
                  echo '<th>Shared Snapshot Link</th>';
                echo '</thead>';
                echo '<tbody>';
                  process_urls();
              //below tags closed in process_urls() function
              //   echo '</tbody>';
              // echo '</table>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div style="margin-top: 75px">
    <footer class="footer">
      <div class="container">
        <span class="text-muted">Build by VSE Team - 2018 (Support: dl-rigor-vse@akamai.com)</span>
      </div>
    </footer>
  </div>
</body>
<script>
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();
  </script>

  <script>
  $(function() {
    $('input[type=file]').change(function(){
      var t = $(this).val();
      var labelText = 'File : ' + t.substr(12, t.length);
      $(this).prev('label').text(labelText);
    })
  });
  }
  </script>
</html>
<?php
function process_urls(){
  $table_to_mail_header = create_email_table("Status", "URL", "Rigor Test Name", "Test Location", "Snapshot Link", "Shared Snapshot Link");
  $table_to_mail = "";
  $table_to_mail_all = "";
  $api_key = "E2C8D577C7CC2464472BF6BE852353FE";
  $api_url = "https://optimization-api.rigor.com/v2/tests";
  $test_name = "API Full Website - ";
  $start_url = "";
  $device_type = "DesktopChrome";
  $user_agent = "Mozilla/5.0 Windows NT 10.0; Win64; x64 AppleWebKit/537.36 KHTML, like Gecko Chrome/65.0.3286.0 Safari/537.36 Rigor";
  $recurring_test = "false";
  $test_region = "";
  $download_bandwidth = "20000";
  $upload_bandidth = "5000";
  $latency = "28";
  for($i = 0 ; $i < count($_SESSION['array_selects']) ; $i++) {
    $formated_select = $_SESSION['array_selects'][$i];
    $formated_select = rtrim($formated_select);
    $replaced_formated_select = str_replace(".","_", $formated_select);
    $selected_val_region = $_POST[$replaced_formated_select];
    $selected_val_region = rtrim($selected_val_region);
    $replaced_formated_select_dot = str_replace("_",".", $formated_select);
    $replace_sufix = array("https://", "http://");
    $replaced_formated_select_dot = str_replace($replace_sufix, "", $formated_select);
    //If element related
    if($selected_val_region === "Website not responding"){
      create_results_table("<span class=\"badge badge-danger\" style=\"font-size: 12px\">Error</span>", $replaced_formated_select_dot, "None", "None", "Website not responding", "");
      // echo '<th scope="row">'.$replaced_formated_select_dot.'<span class="badge badge-danger" style="margin-left: 5px">Error</span></th>';
      // echo '<td><input class="form-control form-control-sm" style="height: calc(1.5rem + 2px); font-size: 75%" type="text" value="Website cannot be resolved by DNS" readonly="readonly" name="'.$replaced_formated_url.'"></td>';
      $table_to_mail = create_email_table("<span class=\"badge badge-danger\" style=\"font-size: 12px\">Error</span>", $replaced_formated_select_dot, "None", "None", "Website not responding", "");
      $table_to_mail_all = $table_to_mail_all.$table_to_mail;
    }
    else{
      $post_data = array("name" => $test_name.$replaced_formated_select_dot, "scan_type" => "WebSite", "start_url" => $replaced_formated_select_dot,
                        "device_type" => $device_type, "user_agent" => $user_agent,
                        "recurring" => $recurring_test, "region" => $selected_val_region,
                        "connection_speed"=> array ("download_bandwidth" => $download_bandwidth ,"upload_bandwidth" => $upload_bandidth, "latency" => $latency));
      $data_string = json_encode($post_data);
      $post_headers = array('API-KEY: '.$api_key, 'Content-Type: application/json', 'Accept: application/json');
      $options = [  CURLOPT_URL        => $api_url,
                    CURLOPT_POST       => true,
                    CURLOPT_POSTFIELDS => $data_string,
                    CURLOPT_HTTPHEADER => $post_headers,
                    CURLOPT_RETURNTRANSFER => true,
                  ];
      $ch=curl_init();
      curl_setopt_array($ch, $options);
      $results = curl_exec($ch);
      $json_string_decode = json_decode($results);
      //If POST operations results in an error
      if($json_string_decode->{'IsError'}) {
        if($json_string_decode->{'message'} === "Test name already exists.")
        {
          $rigor_test_name = $test_name.$replaced_formated_select_dot;
        }else{
          $rigor_test_name = "None";
        }
        create_results_table("<span class=\"badge badge-info\" style=\"font-size: 12px\">Info</span>", $replaced_formated_select_dot, $rigor_test_name, $selected_val_region, $json_string_decode->{'message'}, $json_string_decode->{'message'});
        $table_to_mail = create_email_table("<span class=\"badge badge-info\" style=\"font-size: 12px\">Info</span>", $replaced_formated_select_dot, $rigor_test_name, $selected_val_region, $json_string_decode->{'message'}, "");
        $table_to_mail_all = $table_to_mail_all.$table_to_mail;
      }
      //If POST does not return Error
      else{
        $rigor_test_name = $test_name.$replaced_formated_select_dot;
        $api_url_test = $api_url."/".rtrim($json_string_decode->{'test_id'})."/snapshots";
        $post_data = array();
        $data_string = json_encode($post_data);
        $post_headers = array('API-KEY: '.$api_key, 'Content-Type: application/json', 'Accept: application/json');
        $options = [
             CURLOPT_URL        => $api_url_test,
             CURLOPT_POST       => true,
             CURLOPT_POSTFIELDS => $data_string,
             CURLOPT_HTTPHEADER => $post_headers,
             CURLOPT_RETURNTRANSFER => true,
           ];
        $ch=curl_init();
        curl_setopt_array($ch, $options);
        $results = curl_exec($ch);
        $json_string_decode = json_decode($results);
        //echo $results."</br>";
        curl_close($ch);

        if($json_string_decode->{'snapshot_id'}){
          create_results_table("<span class=\"badge badge-success\" style=\"font-size: 12px\">".$json_string_decode->{'status'}."</span>", $replaced_formated_select_dot, $rigor_test_name, $selected_val_region, $json_string_decode->{'snapshot_url'}, $json_string_decode->{'snapshot_url_guest'});
          $table_to_mail = create_email_table("<span class=\"badge badge-success\" style=\"font-size: 12px\">".$json_string_decode->{'status'}."</span>", $replaced_formated_select_dot, $rigor_test_name, $selected_val_region, $json_string_decode->{'snapshot_url'}, $json_string_decode->{'snapshot_url_guest'});
          $table_to_mail_all = $table_to_mail_all.$table_to_mail;
        }
        else{
          create_results_table("<span class=\"badge badge-danger\" style=\"font-size: 12px\">Error</span>", $replaced_formated_select_dot, $rigor_test_name, $selected_val_region, $json_string_decode->{'message'}, "");
          $table_to_mail = create_email_table("<span class=\"badge badge-danger\" style=\"font-size: 12px\">Error</span>", $replaced_formated_select_dot, $rigor_test_name, $selected_val_region, $json_string_decode->{'message'}, "");
          $table_to_mail_all = $table_to_mail_all.$table_to_mail;
        }
      }
    }
  }
  //to close table from
    echo '</tbody>';
  echo '</table>';
  echo '</div>';
  send_mail($_SESSION['mail_report'], $table_to_mail_header, $table_to_mail_all);
}

//Function to create table with testing results
function create_results_table($status, $url, $test_name_rigor, $test_location, $snap_url, $shared_snap_url){
  echo '<tr>';
    echo '<th scope="row">'.$status.'</th>';
    echo '<td>'.$url.'</td>';
    echo '<td>'.$test_name_rigor.'</td>';
    echo '<td>'.$test_location.'</td>';
    echo '<td>'.$snap_url.'</td>';
    echo '<td>'.$shared_snap_url.'</td>';
  echo '</tr>';
}

//Function to create tables for email
function create_email_table($status, $url, $test_name_rigor, $test_location, $snap_url, $shared_snap_url){
  $table_line =   '<tr><th scope="row">'.$status.'</th>'.
                  '<td>'.$url.'</td>'.
                  '<td>'.$test_location.'</td>'.
                  '<td>'.$test_name_rigor.'</td>'.
                  '<td>'.$snap_url.'</td>'.
                  '<td>'.$shared_snap_url.'</td>'.
                  '</tr>';
  return $table_line;
}

//Function to send email
function send_mail($to_mail, $table_header, $body){
  $table_header_mail = '<div style="margin-top: 25px"><table border=1>'.
          '<thead>'.$table_header.'</thead><tbody>';
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';  //gmail SMTP server
  $mail->SMTPAuth = true;
  $mail->Username = 'no.reply.api.fullwebsite@gmail.com';   //username
  $mail->Password = 'Mrcrsj84A!';   //password
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;                    //SMTP port
  $mail->setFrom('no.reply.api.fullwebsite@gmail.com', '');
  $mail->addAddress($to_mail, '');

  $mail->isHTML(true);

  $mail->Subject = 'Report - API Request(s) for Rigor Full WebSite Analysis';
  $mail->Body    = '<h2>Report - API Request(s) for Rigor Full WebSite Analysis:</h2>'.$table_header_mail.$body.'</tbody></table></div>';

  if (!$mail->send()) {
      echo '<span style="font-size: 14px" class="badge badge-danger">Failed sending mail!</span>';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
  } else {
    echo '<div style="margin-top: 25px">';
      echo '<p><span style="font-size: 14px" class="badge badge-success">Email Sent!</span></p>';
    echo '</div>';
  }
}

//Function to validate email
function validate_email($email){
  $exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
    if(eregi($exp,$email)){
      if(checkdnsrr(array_pop(explode("@",$email)),"MX")){
        return true;
      }
      else{
        return false;
      }
    }else{
    return false;
    }
}

//Funtion to create the Step 2 results table
function create_results_table_in_file($url, $region_code_array){
  echo '<tr>';
    echo '<th scope="row">'.$url.'</th>';
    add_region_button($url, $region_code_array);
  echo '</tr>';
}

//Function to add button with region button
function add_region_button($url_for_select, $region_array){
  $replace_url_for_select = str_replace(".","_",$url_for_select);
  $replace_url_for_select = rtrim($replace_url_for_select);
  echo '<td><select class="custom-select custom-select-sm" style="height: calc(1.5rem + 2px)" name="'.$replace_url_for_select.'">';
    echo '<option selected>na-us-virginia</option>';
    foreach ($region_array as &$region_name) {
      echo '<option value="'.$region_name.'">'.$region_name.'</option>';
    }
  echo '</select></td>';
}

//Function to generate table for region test selection
function process_in_file_to_table(){
  $array_selects = array();
  $_SESSION['array_dns_nok'] = array();
  // $_SESSION['array_urls_select'] = array();
  $path = "uploads/";
  $path = $path . basename( $_FILES['bulk_full_web']['name']);
  //eu-central-1 removed as per message after request: Test location not found or no longer exists
  //ap-northeast-2 removed as per message after request: Test location not found or no longer exists
  $region_test_codes = array( "ap-jp-tokyo","ap-kr-seoul","ap-in-mumbai","ap-sg-singapore",
                              "ap-au-sydney","ap-hk-hong-kong","na-ca-montreal","na-us-dallas","eu-de-frankfurt",
                              "eu-ie-dublin","eu-it-milan","eu-gb-london","eu-nl-amsterdam",
                              "na-us-chicago","sa-br-sao-paulo","na-us-virginia","na-us-ohio","na-us-california",
                              "na-us-oregon","na-us-miami","sa-cl-chile","sa-ar-argentina","na-mx-mexico-city");

  if(!empty($_FILES['bulk_full_web'])) {
    if($_FILES['bulk_full_web']['type'] !== "text/plain"){
      echo '<div class="alert alert-danger" role="alert"><span class="badge badge-danger" style="margin-right: 10px">ERROR</span>File is not PLAIN TEXT.</div>';
    }
    else{
      echo '<p class="card-text">Default location: na-us-virginia</p>';
      if(move_uploaded_file($_FILES['bulk_full_web']['tmp_name'], $path)){
        echo '<table id="fullwebsite_results" class="table table-striped table-sm" style="font-size: 12px">';
          echo '<thead>';
            echo '<th scope="row">URL</th>';
            echo '<th>Test Region</th>';
          echo '</thead>';
          echo '<tbody>';
            $file = fopen("$path","r");
              while(! feof($file)){
                $url_to_analize = fgets($file);
                $url_to_analize = rtrim($url_to_analize);
                $ip = rtrim(`/usr/bin/curl -Is $url_to_analize | head -n 1`);
                if(!empty($ip)){
                  array_push($array_selects, $url_to_analize);
                  create_results_table_in_file($url_to_analize, $region_test_codes);
                }
                if(empty($ip)){
                  array_push($array_selects, $url_to_analize);
                  $url_sufixes = array("://", ".");
                  $replaced_formated_url = str_replace($url_sufixes,"_", $url_to_analize);
                  echo '<tr>';
                  echo '<th scope="row">'.$url_to_analize.'<span class="badge badge-danger" style="margin-left: 5px; font-size: 12px">Error</span></th>';
                  echo '<td><input class="form-control form-control-sm" style="height: calc(1.5rem + 2px); font-size: 75%" type="text" value="Website not responding" readonly="readonly" name="'.$replaced_formated_url.'"></td>';
                  echo '</tr>';
                }
              }
          echo '</tbody>';
        echo '</table>';
        echo '<div class="form-row">';
          echo '<div class="">';
            echo '<button class="btn btn-primary btn-sm" name="submit_urls" type="submit">Submit Urls</button>';
          echo '</div>';
        echo '</div>';
      }
    }
  }
  return $array_selects;
}
?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
