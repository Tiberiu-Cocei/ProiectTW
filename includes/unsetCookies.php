<?php 
    function unsetCookiesForAddNewAccount()
    {
        setcookie('siteName', null, -1, '/');
        setcookie("username", null,-1, '/');
        setcookie("address", null, -1, '/');
        setcookie("comments", null,-1, '/');
        setcookie("reminderDate", null,-1, '/');
        setcookie("response", null ,-1, '/');
    }

    function unsetCookie($nameOfCookieToUnset)
    {
        setcookie($nameOfCookieToUnset, null, -1, '/');
    }

    function unsetCookiesForLogout()
    {
        setcookie('userID', null, -1, '/');
        setcookie('usernameConnected', null, -1, '/');
        setcookie('selectedCategoryID', null, -1, '/');
        setcookie('addAccountButton', null, -1, '/TWPM/pages');
        setcookie('allCategoriesCookie', null,-1, '/TWPM/pages');
        setcookie('allAccountsToShowCookie', null, -1, '/TWPM/pages');
        setcookie('response', null, -1, '/');

    }

?>
