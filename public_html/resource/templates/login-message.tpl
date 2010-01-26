{#template MAIN}
{#if $T.login}
ようこそ<strong>{$T.name}</strong>さん | 
<a href="/account/config.php">登録情報の変更</a> | 
<a href="/account/logout.php">ログアウト</a>
{#else}
<a href="/account/login.php">ログイン</a>
{#/if}
{#/template MAIN}