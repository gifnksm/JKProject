{#template MAIN}
<table><tbody>
  <tr>
    <td colspan="2"
        style="text-align: center; font-size: 1.2em; color: red; font-weight: bolder;">
      できること・必要なものを選択してください
    </td>
  </tr>
  {#foreach $T as c}
    <tr>
    <th>
      <img src="{$T.c.icon}" width="32" height="32" alt=""/>
      {$T.c.title}
    </th>
    <td>
    {#foreach $T.c.items as i}
      {#include input root=$T.i}
    {#/for}
    </td>
    </tr>
  {#/for}
</tbody></table>
{#/template MAIN}

{#template input}
  {#if $T.type == 'radio'}
    <div>
    {#foreach $T.selections as s}
      {#if $T.s$index != 0 && !$T.notBreak}<br/>{#/if}
      <label>
        <input type="radio" name="{$T.name}" value="{$T.s.value}"
               {$T.defaultValue == $T.s.value ? 'checked="checked"' : '""'}/>
        {$T.s.title}
      </label>
    {#/for}
    </div>
  {#elseif $T.type == 'checkbox'}
    <div><label>
    <input type="checkbox" name="{$T.name}" value="true"
           {$T.checked ? 'checked="checked"' : '""'}/>
    {$T.title}
    </label></div>
  {#elseif $T.type == 'number'}
    <div>
      <label>
        <input type="checkbox" name="{$T.name + '-check'}" value="true"
                {$T.checked ? 'checked="checked"' : '""'}/>
        {$T.message}
      </label>
      <label>
        <input class="spin" name="{$T.name}"
               value="{$T.defaultValue}" size="3" />
        [{$T.unit}]
      </label>
    </div>
  {#/if}
{#/template input}