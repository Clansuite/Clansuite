<h1>{translate}Activate account{/translate}</h1>
    <p>
        {if $alreadyActivated == 1}{translate}<p class="error">Your account is already activated.{/translate}</p>{/translate}
        {elseif $success == 1}<p>{translate}Your account has been activated.{/translate}</p>
        {else}<p class="error">{translate}Account activation failed. Check if the code and user id are valid in the URL.{/translate}</p>
        {/if}
    </p>