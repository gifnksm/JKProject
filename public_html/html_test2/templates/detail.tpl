{#template MAIN}
{#include icons root=$T}
{#include info root=$T.info}
{#include bfinfo root=$T.bfinfo}
{#/template MAIN}

{#template icons}
<div class="title">
<h2>{$T.info.name}</h2>
<div class="score">
<span class="numer">{$T.scoreValue}</span>
/
<span class="denom">{$T.scoreMax}</span>
</div>
<div class="category-icons">
  {#foreach $T.category.ids as id
  }<img src="/resource/image/icon/{$T.id}.{$T.score[$T.id]}.png"
        alt="{$T.category.names[$T.id]}: {$T.score[$T.id]}"
  />{#/for}
</div>
</div>
{#/template icons}

{#template info}
<div class="info">
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
<div class="comment">
  <h4>お店の人からのコメント</h4>
  <p>{$T.comment}</p>
</div>
{#/if}
</div>
{#/template info}

{#template dtdd}
  {#if $T}
    <dt>{$P.name}</dt>
    <dd>{$T}</dd>
  {#/if}
{#/template dtdd}

{#template bfinfo}
<div class="bfinfo">
<h3>バリアフリー情報</h3>
<dl>
{#foreach $T as c}
  {Detail.parseBarrier($T.c)}
{#/for}
</dl>
</div>
{#/template bfinfo}

