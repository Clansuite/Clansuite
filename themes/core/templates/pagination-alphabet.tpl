<div class="alphabet_navigation">

    {array name="alphabet" values="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z" explode="true" delimiter=","}

    {foreach from=$alphabet item="character"}

        <a href="{$www_root}?mod={currentmodule}&sub=admin&defaultCol=1&defaultSort=asc&searchletter={$character}">{$character}</a>
         &nbsp;

    {/foreach}

</div>