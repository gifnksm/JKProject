{#template MAIN}
<div id="detail-condition-header">
<a href="javascript: void(0);" id="detail-condition-complete-link">条件指定完了</a>
</div>
<dl>
  {#foreach $T as c}
    <dt><a href="javascript: void(0);">
      <img src="{$T.c.icon}" width="32" height="32" alt=""/>
      {$T.c.title}
    </a></dt>
    <dd>
    {#foreach $T.c.items as i}
      {#if $T.i.type == 'radio'}
        <div>
        {#foreach $T.i.selections as s}
          {#if $T.s$index != 0}<br/>{#/if}
          <label>
            <input type="radio" name="{$T.i.name}" value="{$T.s.value}"
                   {$T.i.defaultValue == $T.s.value ? 'checked="checked"' : '""'}/>
            {$T.s.title}
          </label>
        {#/for}
        </div>
      {#elseif $T.i.type == 'checkbox'}
        <div><label>
        <input type="checkbox" name="{$T.i.name}" value="true"
               {$T.i.checked ? 'checked="checked"' : '""'}/>
        {$T.i.title}
        </label></div>
      {#/if}
    {#/for}
    </dd>
  {#/for}
</dl>
{#/template MAIN}