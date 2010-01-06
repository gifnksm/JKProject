{#template MAIN}
<div class="info-window">
   <h2>{ $T.name }</h2>
   <table><tr><td>
   {#if $T.addr || $T.tel || $T.url}
     <ul>
       {#if $T.addr  }<li>{ $T.addr }</li>{#/if}
       {#if $T.tel   }<li>{ $T.tel  }</li>{#/if}
       {#if $T.url   }<li>{* ホスト名だけ抽出 *}
         <a href="{$T.url}">{$T.url.replace(/^http:\/\/|\/.*/g, '')}</a>
       </li>{#/if}
     </ul>
   {#/if}
   </td><td>
   {#if $T.infoImage }
     <img src="{$T.infoImage.url}" alt="写真"
          width="{$T.infoImage.width}" height="{$T.infoImage.height}" />
   {#/if}
   </td></tr></table>
   </div>
{#/template MAIN}