<h1>Activate password</h1>
    <p>
        {php}
            if ($noNewPassword) echo '<p>There is no new password to activate.</p>';
            else if ($success) echo '<p>New password has been activated.</p>';
            else echo '<p class="error">New password activation failed. Check if the code and user id are valid in the URL.</p>';
        {/php}
    </p>