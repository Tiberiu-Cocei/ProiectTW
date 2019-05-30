<!DOCTYPE html>
<?php
  session_start();
  if($_SESSION['username'] === null) header("Location:./Login.php");
?>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <title>Main page</title>
    <link rel="stylesheet" href="../public/css/main_page.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../public/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../public/images/favicon/favicon-96x96.png">
</head>

<body>
<nav>
    <ul class="menuBar">
        <li class="myAccount">       <a style="color:#f6cd61;">                                    Signed in as: <?php echo $_SESSION['username']; ?></a></li>
        <li class="changePassword">  <a style="color:#f6cd61;" href="./account/changePassword.php">Change password                                   </a></li>
        <li class="exportData">      <a style="color:#f6cd61;" href="export.php">                  Export data                                       </a></li>
        <li class="generatePassword"><a style="color:#f6cd61;" href="./generate_password.php">     Generate safe password                            </a></li>
        <li class="logout">          <a style="color:#f6cd61;" href="./account/logout.php">        Logout                                            </a></li>
    </ul>
</nav>

<div class="grid-container">
    <div class="center column" src="submain.php">
      <button onclick="location.href = 'new_category.php';" id="addCategory" type="button" class="buttonReversed middle innerButton"><b>Add new category</b></button>
      <button type="button" id="Frequency" style="margin-top:20px" class="buttonReversed middle innerButton">                        <b>Accounts by use frequency</b></button>
      <button type="button" id="Strength" class="buttonReversed middle innerButton"><b>Accounts by password strength</b></button>
      <button type="button" class="buttonReversed middle innerButton">              <b>Categories</b></button>
      


      <?php
      include_once '../../includes/apiCall.php';
      //$categoriesApi = 'http://localhost/api/account/get_by_usage.php?id_utilizator=aiciIdul

      // function echoCategoryButton($category_name)
      // {
      //   echo "<button onclick=\"location.href = '#"
      //       .$category_name
      //       ."';\""
      //       ."id=\""
      //       .$category_name
      //       ."\" "
      //       ."type=\"button\" class=\"button middle innerButton\">"
      //       ."<b>".$category_name."</b></button>";
      // }

      // function echoCategoryButtonHardcoded($category_name)
      // {
      //   echo "<button onclick=\"location.href = '#Social';\" id=\"Social\" 
      //   type=\"button\" class=\"button middle innerButton\"><b>Social</b>
      //   </button>"; 

      // }


        $userApi = 'http://localhost/TWPM/api/user/get_by_name.php?username='.$_SESSION['username']; 

        $make_call = ApiCall('GET', $userApi, json_encode($_SESSION['username']));

        //echo $make_call; 

        $response = json_decode($make_call, true);

        $data     = $response['id_utilizator'];

        if($data === "Could not find any user with given username." || $data === null) {
            header("Location: ./Login.php");
        }
        else 
        {
          echoCategoryButtonHardcoded("Social1"); 
          echoCategoryButtonHardcoded("Social2"); 
          echoCategoryButtonHardcoded("Social3"); 
          echoCategoryButtonHardcoded("Social4"); 

          
        }

?>


      <!-- <?php 
      function echoCategoryButtonHardcoded($category_name)
      {
      ?>
        <button onclick="location.href = '#Social';" id="Social" type="button" class="button middle innerButton"><b>Social</b>
        </button>
        <?php
      }
      ?> -->
      

    </div>
    
    <div class="center column" src="accounts.php">
      <button onclick="location.href = 'new_account.php';" id="addSite" type="button" class="buttonReversed middle innerButton"><b>Add new account</b></button>

      <div class="textWrapper">
          <h2>Username: JohnDoe1990</h2>
          <h2>Password: ********</h2>
          <button onclick="location.href = '#showPassword';" id="showPassword1" type="button"
                  class="button "><b>Show password</b></button>
          <h2>Web address: <a href="#webLink">www.steam.com</a></h2>
          <h2>Comments: Main steam game library</h2>
          <h2>Password Safety Level: 1 - Very low</h2>
          <h2>Reset reminder: None</h2>
          <button onclick="location.href = 'edit_account.php';" id="edit1" type="button"
                  class="button "><b>Edit account info</b></button>
          <button onclick="location.href = '#delete';" id="delete1" type="button"
                  class="button buttonMargin"><b>Delete entry</b></button>
      </div>
      <div class="textWrapper">
          <h2>Username: JohnDoe1991</h2>
          <h2>Password: ***********</h2>
          <button onclick="location.href = '#showPassword';" id="showPassword2" type="button"
                  class="button "><b>Show password</b></button>
          <h2>Web address: <a href="#webLink">www.steam.com</a></h2>
          <h2>Comments: Secondary library</h2>
          <h2>Password Safety Level: 3 - Medium</h2>
          <h2>Reset reminder: 26 March 2019</h2>
          <button onclick="location.href = 'edit_account.php';" id="edit2" type="button"
                  class="button "><b>Edit account info</b></button>
          <button onclick="location.href = '#delete';" id="delete2" type="button"
                  class="button buttonMargin"><b>Delete entry</b></button>
      </div>
    </div>
</div>

</body>
</html>
