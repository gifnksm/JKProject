{#template MAIN}
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
        {#if $T.i.items}
        <div>
        {#foreach $T.i.items as s}
          <div><label>
            <input type="checkbox" name="{$T.s.name}" value="{$T.s.value}"
                   {$T.s.checked ? 'checked="checked"' : '""'}/>
            {$T.s.title}
          </label></div>
        {#/for}
        </div>
        {#/if}
      {#/if}
    {#/for}
    </dd>
  {#/for}
</dl>
{#/template MAIN}