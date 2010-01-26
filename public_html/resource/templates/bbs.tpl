{#template MAIN}
<h3>寄せられたご意見</h3>
{#if $T.comments.length == 0}
  <p>まだありません</p>
{#else}
  {#foreach $T.comments as c}
  <div class="comment">
    <div class="data">
      <em>投稿者：</em> <span class="username">{$T.c.username}</span>さん
      (<span class="date">{$T.c.date}</span>)
    </div>
    <p>{$T.c.text}</p>
  </div>
  {#/for}
{#/if}
{#if $T.login}
<div class="submit">
<h3>ご意見募集</h3>
<p>お店へのご意見を書き込めます</p>
<form id="bbs-comment">
  <table>
  <tbody>
  <tr><th>お名前</th><td>{$T.name}</td></tr>
  <tr><th>コメント</th>
  <td><textarea rows="5" cols="50" name="comment"></textarea></td></tr>
  <tr><td></td><td><input type="submit" value="書き込む"/></td></tr>
  </tbody>
  </table>
  <input type="hidden" name="facility_id" value="{$T.facility_id}"/>
</form>
</div>
{#/if}
{#/template MAIN}