<h1>{translate}Activate password{/translate}</h1>
    <p>
        {if $noNewPassword == 1}<p>{translate}There is no new password to activate.{/translate}</p>
        {elseif $success == 1}<p>{translate}New password has been activated.{/translate}</p>
        {else}<p class="error">{translate}New password activation failed. Check if the code and user id are valid in the URL.{/translate}</p>
        {/if}
    </p>