{#template MAIN}
<h2>店舗詳細情報：{$T.info.name}</h2>
{#include info root=$T.info}
{#include bfinfo root=$T.bfinfo}
{#/template MAIN}

{#template info}
<h3>施設データ</h3>
<dl>
  {#param name=name value="アクセス"}{#include dtdd root=$T.access}
  {#param name=name value="電話番号"}{#include dtdd root=$T.tel}
  {#param name=name value="Fax番号"}{#include dtdd root=$T.fax}
  {#param name=name value="住所"}{#include dtdd root=$T.addr}
  {#if $T.open}
    <dt>営業時間</dt>
    <dd>{Detail.parseOpen($T.open)}</dd>
  {#/if}
  {#param name=name value="休日"}{#include dtdd root=$T['shop-holiday']}
  {#if $T.url}
    <dt>URL</dt>
    <dd><a href="{$T.url}">{$T.url}</a></dd>
  {#/if}
  {#param name=name value="調査日"}{#include dtdd root=$T.date}
</dl>
{#if $T.comment}
  <h4>お店の人からのコメント</h4>
  <div class="comment">
    {$T.comment}
  </div>
{#/if}
{#/template info}

{#template dtdd}
  {#if $T}
    <dt>{$P.name}</dt>
    <dd>{$T}</dd>
  {#/if}
{#/template dtdd}

{#template bfinfo}
<h3>バリアフリー情報</h3>
<dl>
{#foreach $T as c}
  {Detail.parseBarrier($T.c)}
{#/for}
</dl>
{#/template bfinfo}

