<h1>Activate account</h1>
    <p>
        {php}
            if ($alreadyActivated) echo '<p>Your account is already activated.</p>';
            else if ($success) echo '<p>Your account has been activated.</p>';
            else echo '<p class="error">Account activation failed. Check if the code and user id are valid in the URL.</p>';
        {/php}

    </p>