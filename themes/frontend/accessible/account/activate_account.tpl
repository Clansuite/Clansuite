<h1>{t}Activate account{/t}</h1>
{if $alreadyActivated == 1}{t}<p class="error">Your account is already activated.{/t}</p>{/t}
{elseif $success == 1}<p>{t}Your account has been activated.{/t}</p>
{else}<p class="error">{t}Account activation failed. Check if the code and user id are valid in the URL.{/t}</p>
{/if}