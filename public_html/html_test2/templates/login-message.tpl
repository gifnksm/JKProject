{#template MAIN}
{#if $T.login}
ようこそ<strong>{$T.name}</strong>さん |
<a href="/config.php">登録情報の変更</a> |
<a href="/logout.php">ログアウト</a>
{#else}
<a href="/login.php">ログイン</a>
{#/if}
{#/template MAIN}