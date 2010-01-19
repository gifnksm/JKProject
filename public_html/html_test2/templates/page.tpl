{#template MAIN}
<table>
  {#foreach $T as item}
    <tr class="item">
      <td>
        <a href="javascript: GMap.showInfoWindow({$T.item.id});">
          <img src="/resource/image/pin/{num2alph($T.item$index)}.{$T.item.score.color}.png"
               width="20" height="34" class="pin" />
        </a>
      </td>
      <td>
        <div>
          <span class="name">
            <a href="javascript: GMap.showInfoWindow({$T.item.id});">
              {$T.item.name}
            </a>
          </span>
          -
          <a href="javascript: showDetail({$T.item.id});">詳細&raquo;</a>
        </div>
        {#if $T.item.images }
          <div>
            <a href="javascript: GMap.showInfoWindow({$T.item.id});">
              <span class="list-image"
                   style="background-image: url({$T.item.images[0].url})"></span>
            </a>
          </div>
        {#/if}
        <div class="detail">
          {#if $T.item.addr }<span class="addr">{$T.item.addr}</span>{#/if}
          {#if $T.item.tel  }
            <span class="tel">
              <img src="/resource/image/phone.gif"
                   width="16" height="16" alt="Tel: " />{$T.item.tel}
            </span>
          {#/if}
        </div>
        <div class="category-icons">
          {#foreach $P.category.ids as cid
          }{#param name=cat value=$T.item.score.detail[$T.cid]
          }<img src="/resource/image/icon/{$T.cid}.{$P.cat.color}.png"
                alt="{$P.category.names[$T.cid]}: {$P.cat.color}"
                title="{$P.category.names[$T.cid]}: {$P.cat.message || ''}"
          />{#/for}
        </div>
      </td>
    </tr>
  {#/for}
</table>
{#/template MAIN}